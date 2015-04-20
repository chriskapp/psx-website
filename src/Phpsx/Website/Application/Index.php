<?php

namespace Phpsx\Website\Application;

use PSX\Controller\ViewAbstract;

class Index extends ViewAbstract
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
			'motd'  => 'Welcome, PSX is a framework written in PHP to create RESTful APIs',
			'links' => [[
				'rel'  => 'self',
				'href' => $this->reverseRouter->getUrl(__CLASS__),
			],[
				'rel'  => 'download',
				'href' => $this->reverseRouter->getUrl('Phpsx\Website\Application\Download'),
			],[
				'rel'  => 'documentation',
				'href' => $this->reverseRouter->getUrl('Phpsx\Website\Application\Documentation'),
			],[
				'rel'  => 'blog',
				'href' => $this->reverseRouter->getUrl('Phpsx\Website\Application\Blog::doIndex'),
			]]
		]);
	}
}
