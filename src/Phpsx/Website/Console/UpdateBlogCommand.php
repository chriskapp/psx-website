<?php

namespace Phpsx\Website\Console;

use DateTime;
use Doctrine\DBAL\Connection;
use Phpsx\Website\Slugify;
use PSX\Atom;
use PSX\Data\Importer;
use PSX\Data\RecordInterface;
use PSX\Http\Message;
use PSX\Http\Stream\TempStream;
use PSX\Sql\TableManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateBlogCommand extends Command
{
	protected $tableManager;
	protected $importer;
	protected $feedFile;
	protected $slugify;

	public function __construct(TableManager $tableManager, Importer $importer, $feedFile)
	{
		parent::__construct();

		$this->tableManager = $tableManager;
		$this->importer     = $importer;
		$this->feedFile     = $feedFile;
		$this->slugify      = new Slugify();
	}

	protected function configure()
	{
		$this
			->setName('website:update_blog')
			->setDescription('Updates all entries from the blog xml feed into an sqlite database');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$table   = $this->tableManager->getTable('Phpsx\Website\Table\Blog');
		$atom    = new Atom();
		$message = new Message(['Content-Type' => 'application/atom+xml']);
		$message->setBody(new TempStream(fopen($this->feedFile, 'r')));

		$postCount = $putCount = 0;
		$atom      = $this->importer->import($atom, $message);

		foreach($atom as $entry)
		{
			$row    = $table->getOneById($entry->getId());
			$author = $this->getFirstAuthor($entry);

			if(!$author instanceof Atom\Person)
			{
				throw new \RuntimeException(sprintf('No author provided for entry %s', $entry->getId()));
			}

			if(!$row instanceof RecordInterface)
			{
				// the blog entry does not exist create it
				$table->create([
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
			}
			else
			{
				if($row->getUpdated()->format('Y-m-d H:i:s') != $entry->getUpdated()->format('Y-m-d H:i:s'))
				{
					// if the update date has change update the entry
					$table->update([
						'title'      => $entry->getTitle(),
						'titleSlug'  => $this->slugify->slugify($entry->getTitle()),
						'authorName' => $author->getName(),
						'authorUri'  => $author->getUri(),
						'updated'    => $entry->getUpdated(),
						'summary'    => $entry->getSummary(),
						'category'   => $this->getCategories($entry),
						'content'    => $entry->getContent()->getContent(),
					], [
						'id' => $entry->getId()
					]);

					$putCount++;
				}
			}
		}

		$output->writeln(sprintf('Created %s and updated %s entries', $postCount, $putCount));
	}

	protected function getCategories(Atom\Entry $entry)
	{
		$result     = array();
		$categories = $entry->getCategory();

		if(!empty($categories))
		{
			foreach($categories as $category)
			{
				$result[] = $this->slugify->slugify($category->getTerm());
			}
		}

		return $result;
	}

	protected function getFirstAuthor(Atom\Entry $entry)
	{
		$authors = $entry->getAuthor();

		if(!empty($authors))
		{
			return current($authors);
		}

		return null;
	}
}
