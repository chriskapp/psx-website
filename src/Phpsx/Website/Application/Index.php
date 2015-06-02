<?php

namespace Phpsx\Website\Application;

use PSX\Controller\ViewAbstract;
use PSX\Data\WriterInterface;

class Index extends ViewAbstract
{
	/**
	 * @Inject
	 * @var PSX\Loader\ReverseRouter
	 */
	protected $reverseRouter;

	/**
	 * @Inject
	 * @var PSX\Data\WriterFactory
	 */
	protected $writerFactory;

	public function doIndex()
	{
		$this->setBody([
			'motd'  => 'Welcome, PSX is a framework written in PHP to create REST APIs',
			'links' => [[
				'rel'  => 'self',
				'href' => $this->reverseRouter->getUrl(__CLASS__),
			],[
				'rel'  => 'download',
				'href' => $this->reverseRouter->getUrl('Phpsx\Website\Application\Download::doIndex'),
			],[
				'rel'  => 'documentation',
				'href' => $this->reverseRouter->getUrl('Phpsx\Website\Application\Documentation::doIndex'),
			],[
				'rel'  => 'blog',
				'href' => $this->reverseRouter->getUrl('Phpsx\Website\Application\Blog::doIndex'),
			]]
		]);
	}

	public function doHumans()
	{
		$this->writerFactory->setContentNegotiation('*/*', WriterInterface::TEXT);
	}
}
