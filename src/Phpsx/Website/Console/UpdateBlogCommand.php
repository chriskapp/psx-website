<?php

namespace Phpsx\Website\Console;

use Phpsx\Website\Slugify;
use Phpsx\Website\Table;
use PSX\Data\Payload;
use PSX\Data\Processor;
use PSX\Model\Atom\Atom;
use PSX\Model\Atom\Entry;
use PSX\Model\Atom\Person;
use PSX\Record\RecordInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateBlogCommand extends Command
{
    protected $blogTable;
    protected $processor;
    protected $feedFile;
    protected $slugify;

    public function __construct(Table\Blog $blogTable, Processor $processor, $feedFile)
    {
        parent::__construct();

        $this->blogTable = $blogTable;
        $this->processor = $processor;
        $this->feedFile  = $feedFile;
        $this->slugify   = new Slugify();
    }

    protected function configure()
    {
        $this
            ->setName('website:update_blog')
            ->setDescription('Updates all entries from the blog xml feed into an sqlite database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $postCount = $putCount = 0;
        $data      = file_get_contents($this->feedFile);

        /** @var Atom $atom */
        $atom  = $this->processor->read(Atom::class, Payload::create($data, 'application/atom+xml'));

        foreach ($atom->getEntry() as $entry) {
            /** @var Entry $entry */
            $row    = $this->blogTable->getOneById($entry->getId());
            $author = $this->getFirstAuthor($entry);

            if (!$author instanceof Person) {
                throw new \RuntimeException(sprintf('No author provided for entry %s', $entry->getId()));
            }

            if (!$row instanceof RecordInterface) {
                // the blog entry does not exist create it
                $this->blogTable->create([
                    'id'         => $entry->getId(),
                    'title'      => $entry->getTitle(),
                    'titleSlug'  => $this->slugify->slugify($entry->getTitle()),
                    'authorName' => $author->getName(),
                    'authorUri'  => $author->getUri(),
                    'updated'    => $entry->getUpdated(),
                    'summary'    => $entry->getSummary(),
                    'category'   => $this->getCategories($entry),
                    'content'    => $entry->getContent()->getContent(),
                ]);

                $postCount++;
            } else {
                if ($entry->getUpdated() > $row->updated) {
                    // if the update date has change update the entry
                    $this->blogTable->update([
                        'id'         => $entry->getId(),
                        'title'      => $entry->getTitle(),
                        'titleSlug'  => $this->slugify->slugify($entry->getTitle()),
                        'authorName' => $author->getName(),
                        'authorUri'  => $author->getUri(),
                        'updated'    => $entry->getUpdated(),
                        'summary'    => $entry->getSummary(),
                        'category'   => $this->getCategories($entry),
                        'content'    => $entry->getContent()->getContent(),
                    ]);

                    $putCount++;
                }
            }
        }

        $output->writeln(sprintf('Created %s and updated %s entries', $postCount, $putCount));
    }

    protected function getCategories(Entry $entry)
    {
        $result     = array();
        $categories = $entry->getCategory();

        if (!empty($categories)) {
            foreach ($categories as $category) {
                $result[] = $this->slugify->slugify($category->getTerm());
            }
        }

        return $result;
    }

    protected function getFirstAuthor(Entry $entry)
    {
        $authors = $entry->getAuthor();

        if (!empty($authors)) {
            return current($authors);
        }

        return null;
    }
}
