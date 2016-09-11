<?php

namespace Phpsx\Website\Console;

use Doctrine\DBAL\Connection;
use Phpsx\Website\DatabaseSchema;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSchemaCommand extends Command
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        parent::__construct();

        $this->connection = $connection;
    }

    protected function configure()
    {
        $this
            ->setName('website:create_schema')
            ->setDescription('Creates the sqlite database schema');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $schema  = $this->connection->getSchemaManager()->createSchema();
        $queries = $schema->getMigrateToSql(DatabaseSchema::getSchema(), $this->connection->getDatabasePlatform());

        foreach ($queries as $query) {
            $output->writeln(sprintf('Execute query: %s', $query));

            $this->connection->query($query);
        }
    }
}
