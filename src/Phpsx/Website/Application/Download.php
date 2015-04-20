<?php

namespace Phpsx\Website\Application;

use PSX\Controller\ViewAbstract;
use PSX\Sql;

class Download extends ViewAbstract
{
	/**
	 * @Inject
	 * @var PSX\Sql\TableManager
	 */
	protected $tableManager;

	public function onLoad()
	{
		parent::onLoad();

		$releases = $this->tableManager->getTable('Phpsx\Website\Table\Release')->getAll(0, 1, 'publishedAt', Sql::SORT_DESC);
		$release  = current($releases);

		$this->setBody([
			'links' => [[
				'rel'         => 'composer',
				'title'       => 'Composer',
				'href'        => 'https://packagist.org/packages/psx/sample',
				'description' => 'Sample project which contains a basic API to get started with PSX',
			],[
				'rel'         => 'vagrant',
				'title'       => 'Vagrant',
				'href'        => 'https://github.com/k42b3/psx-vagrant',
				'description' => 'Repository which contains a Vagrant-Box with the PSX sample project',
			],[
				'rel'         => 'download',
				'title'       => $release['tagName'],
				'href'        => $release['htmlUrl'],
				'description' => 'Latest repository tag',
			]]
		]);
	}
}
