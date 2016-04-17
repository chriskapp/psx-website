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

	public function doIndex()
	{
		$this->setBody([
			'links' => [[
				'rel'         => 'manual',
				'title'       => 'Manual',
				'href'        => 'http://psx.readthedocs.org',
				'description' => 'The official manual of PSX',
			],[
				'rel'         => 'forum',
				'title'       => 'Forum',
				'href'        => 'https://groups.google.com/forum/#!forum/phpsx',
				'description' => 'Forum for questions about PSX ',
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
