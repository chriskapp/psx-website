<?php

namespace Phpsx\Website\Application\Blog;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Http\Exception as StatusCode;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;
use PSX\Http\Stream\TempStream;
use PSX\Record\RecordInterface;
use PSX\Sql\Condition;

class Feed extends ViewAbstract
{
    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        $response->setHeader('Content-Type', 'application/atom+xml');
        $response->getBody()->write(file_get_contents($this->config['blog_file']));
    }
}
