<?php

namespace Phpsx\Website\Application;

use PSX\Framework\Controller\ControllerAbstract;
use PSX\Http\Stream\TempStream;

class Disclosure extends ControllerAbstract
{
	public function doIndex()
	{
		$this->setHeader('Content-Type', 'application/atom+xml');
		$this->setBody(new TempStream(fopen($this->config['disclosure_file'], 'r')));
	}
}
