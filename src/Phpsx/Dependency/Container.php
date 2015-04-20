<?php

namespace Phpsx\Dependency;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use PSX\Data\writer;
use PSX\Dependency\DefaultContainer;
use Phpsx\Website\Console;
use Symfony\Component\Console\Application;

class Container extends DefaultContainer
{
	/**
	 * @return Doctrine\DBAL\Connection
	 */
	public function getConnection()
	{
		$config = new Configuration();
		$params = array(
			'path'   => PSX_PATH_CACHE . '/blog.db',
			'driver' => 'pdo_sqlite',
		);

		return DriverManager::getConnection($params, $config);
	}

	public function getWriterFactory()
	{
		$writer = parent::getWriterFactory();
		$writer->addWriter(new Writer\Text($this->get('template'), $this->get('reverse_router')), 39);

		return $writer;
	}

	protected function appendConsoleCommands(Application $application)
	{
		parent::appendConsoleCommands($application);

		$application->add(new Console\CreateSchemaCommand($this->get('connection')));
		$application->add(new Console\FetchReleaseCommand($this->get('table_manager'), $this->get('http'), $this->get('config')));
		$application->add(new Console\ShowReleaseCommand($this->get('table_manager')));
		$application->add(new Console\UpdateBlogCommand($this->get('table_manager'), $this->get('importer'), $this->get('config')->get('blog_file')));
		$application->add(new Console\ShowBlogCommand($this->get('table_manager')));
	}
}
