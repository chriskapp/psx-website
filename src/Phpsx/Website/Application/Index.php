<?php

namespace Phpsx\Website\Application;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

class Index extends ViewAbstract
{
    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        $data = [
            'motd'  => 'Welcome, PSX is a framework written in PHP to create REST APIs',
            'links' => [[
                'rel'  => 'self',
                'href' => $this->reverseRouter->getUrl(__CLASS__),
            ], [
                'rel'  => 'download',
                'href' => $this->reverseRouter->getUrl('Phpsx\Website\Application\Download::doIndex'),
            ], [
                'rel'  => 'documentation',
                'href' => $this->reverseRouter->getUrl('Phpsx\Website\Application\Documentation::doIndex'),
            ], [
                'rel'  => 'blog',
                'href' => $this->reverseRouter->getUrl('Phpsx\Website\Application\Blog::doIndex'),
            ]]
        ];

        $this->render($response, __DIR__ . '/../Resource/index.html', $data);
    }
}
