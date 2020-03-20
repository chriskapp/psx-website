<?php

namespace Phpsx\Website\Application;

use PSX\Framework\Controller\ControllerAbstract;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

class Humans extends ControllerAbstract
{
    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        $response->setHeader('Content-Type', 'text/plain');
        $response->getBody()->write(file_get_contents(__DIR__ . '/../Resource/index.txt'));
    }
}
