<?php

namespace Phpsx\Website\Application;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

class Tools extends ViewAbstract
{
    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        $data = [
            'links' => [[
                'rel'         => 'jsonschema',
                'title'       => 'JsonSchema',
                'href'        => $this->reverseRouter->getAbsolutePath(Tools\JsonSchema::class),
                'description' => 'JsonSchema tool to generate a PHP or HTML representation of the provided json schema',
            ], [
                'rel'         => 'openapi',
                'title'       => 'OpenAPI',
                'href'        => $this->reverseRouter->getAbsolutePath(Tools\OpenApi::class),
                'description' => 'OpenAPI tool to generate PHP controller classes and other representations based on an OpenAPI specification',
            ]]
        ];

        $this->render($response, __DIR__ . '/../Resource/tools.html', $data);
    }
}
