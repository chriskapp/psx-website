<?php

namespace Phpsx\Website\Application;

use PSX\Framework\Controller\ViewAbstract;

class Tools extends ViewAbstract
{
    /**
     * @Inject
     * @var \PSX\Framework\Loader\ReverseRouter
     */
    protected $reverseRouter;

    public function doIndex()
    {
        $this->setBody([
            'links' => [[
                'rel'         => 'jsonschema',
                'title'       => 'JsonSchema',
                'href'        => $this->reverseRouter->getAbsolutePath(Tools\JsonSchema::class . '::doIndex'),
                'description' => 'JsonSchema tool to generate a PHP or HTML representation of the provided json schema',
            ], [
                'rel'         => 'openapi',
                'title'       => 'OpenAPI',
                'href'        => $this->reverseRouter->getAbsolutePath(Tools\OpenApi::class . '::doIndex'),
                'description' => 'OpenAPI tool to generate PHP controller classes and other representations based on an OpenAPI specification',
            ]]
        ]);
    }
}
