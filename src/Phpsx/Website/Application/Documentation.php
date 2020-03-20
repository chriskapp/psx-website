<?php

namespace Phpsx\Website\Application;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

class Documentation extends ViewAbstract
{
    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        $data = [
            'links' => [[
                'rel'         => 'manual',
                'title'       => 'Manual',
                'href'        => 'http://psx.readthedocs.org',
                'description' => 'The official manual of PSX',
            ], [
                'rel'         => 'sample',
                'title'       => 'Example',
                'href'        => 'http://example.phpsx.org',
                'description' => 'Example REST API and documentation',
            ], [
                'rel'         => 'source',
                'title'       => 'Github',
                'href'        => 'https://github.com/apioo/psx',
                'description' => 'The repository of PSX',
            ]]
        ];

        $this->render($response, __DIR__ . '/../Resource/documentation.html', $data);
    }
}
