<?php

namespace Phpsx\Website\Console;

use Phpsx\Website\Table\Blog;
use PSX\Sql\Sql;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowBlogCommand extends Command
{
    protected $blogTable;

    public function __construct(Blog $blogTable)
    {
        parent::__construct();

        $this->blogTable = $blogTable;
    }

    protected function configure()
    {
        $this
            ->setName('website:show_blog')
            ->setDescription('Shows all entries from the sqlite database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entries = $this->blogTable->getAll(null, null, 'updated', Sql::SORT_DESC);
        $rows    = array();

        foreach ($entries as $entry) {
            $rows[] = array($entry['id'], $entry['title'], $entry['updated']->format('Y-m-d H:i:s'));
        }

        $table = new Table($output);
        $table
            ->setStyle('compact')
            ->setRows($rows);

        $table->render();

        return 0;
    }
}
