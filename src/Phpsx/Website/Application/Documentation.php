<?php

namespace Phpsx\Website\Application;

use PSX\Controller\ViewAbstract;

class Documentation extends ViewAbstract
{
	/**
	 * @Inject
	 * @var PSX\Loader\ReverseRouter
	 */
	protected $reverseRouter;

	public function onLoad()
	{
		parent::onLoad();

		$this->setBody([
			'links' => [[
				'rel'         => 'manual',
				'title'       => 'Manual',
				'href'        => $this->reverseRouter->getBasePath() . '/doc',
				'description' => 'The official manual of PSX',
			],[
				'rel'         => 'api',
				'title'       => 'API',
				'href'        => $this->reverseRouter->getBasePath() . '/api',
				'description' => 'The official API of PSX',
			],[
				'rel'         => 'coverage',
				'title'       => 'Test coverage',
				'href'        => $this->reverseRouter->getBasePath() . '/coverage',
				'description' => 'Shows how many code is covered by tests',
			],[
				'rel'         => 'sample',
				'title'       => 'Example',
				'href'        => 'http://example.phpsx.org',
				'description' => 'Example REST API and documentation',
			],[
				'rel'         => 'source',
				'title'       => 'Github',
				'href'        => 'https://github.com/k42b3/psx',
				'description' => 'The repository of PSX',
			]]
		]);
	}
}
