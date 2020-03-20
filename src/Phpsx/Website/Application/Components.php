<?php

namespace Phpsx\Website\Application;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

class Components extends ViewAbstract
{
    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        $projects = json_decode(file_get_contents(__DIR__ . '/../../../../projects.json'));

        $data = [
            'projects' => $projects->primary,
        ];

        $this->render($response, __DIR__ . '/../Resource/components.html', $data);
    }
}
