<?php

namespace Phpsx\Website\Console;

use PSX\Sql\Sql;
use PSX\Sql\TableManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowBlogCommand extends Command
{
    protected $tableManager;

    public function __construct(TableManager $tableManager)
    {
        parent::__construct();

        $this->tableManager = $tableManager;
    }

    protected function configure()
    {
        $this
            ->setName('website:show_blog')
            ->setDescription('Shows all entries from the sqlite database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entries = $this->tableManager->getTable('Phpsx\Website\Table\Blog')->getAll(null, null, 'updated', Sql::SORT_DESC);
        $rows    = array();

        foreach ($entries as $entry) {
            $rows[] = array($entry['id'], $entry['title'], $entry['updated']->format('Y-m-d H:i:s'));
        }

        $table = new Table($output);
        $table
            ->setStyle('compact')
            ->setRows($rows);

        $table->render();
    }
}
