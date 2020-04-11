<?php

namespace Phpsx\Website\Console;

use Phpsx\Website\Table\Release;
use PSX\Sql\Sql;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowReleaseCommand extends Command
{
    protected $tableRelease;

    public function __construct(Release $tableRelease)
    {
        parent::__construct();

        $this->tableRelease = $tableRelease;
    }

    protected function configure()
    {
        $this
            ->setName('website:show_release')
            ->setDescription('Shows all releases from the sqlite database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entries = $this->tableRelease->getAll(null, null, 'publishedAt', Sql::SORT_DESC);
        $rows    = array();

        foreach ($entries as $entry) {
            $rows[] = array($entry['id'], $entry['tagName'], $entry['publishedAt']->format('Y-m-d H:i:s'));
        }

        $table = new Table($output);
        $table
            ->setStyle('compact')
            ->setRows($rows);

        $table->render();

        return 0;
    }
}
