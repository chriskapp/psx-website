<?php

namespace Phpsx\Website\Application\Tools;

use PSX\Api\Resource;
use PSX\Framework\Controller\ViewAbstract;
use PSX\Api\Parser;
use PSX\Api\Generator;
use PSX\Schema\Generator\Html;
use PSX\Schema\Parser\JsonSchema\RefResolver;
use Symfony\Component\Yaml\Yaml;

class OpenApi extends ViewAbstract
{
    /**
     * @Inject
     * @var \Doctrine\Common\Annotations\Reader
     */
    protected $annotationReader;

    public function doIndex()
    {
    }

    public function doGenerate()
    {
        $body = $this->getBody();
        $type = isset($body->type) ? $body->type : 'html';
        $data = isset($body->data) ? $body->data : '';

        try {
            // parse openapi
            $resolver  = new RefResolver();
            $parser    = new Parser\OpenAPI(null, $resolver);
            $resources = $parser->parseAll(json_encode(Yaml::parse($data)));

            // generate output
            $results = [];
            foreach ($resources as $path => $resource) {
                $results[$path] = $this->generateResource($type, $resource);
            }
        } catch (\Exception $e) {
            $results['Response'] = $e->getMessage();
        }

        $this->setBody([
            'in'  => htmlspecialchars($data),
            'out' => $results,
        ]);
    }

    /**
     * @param $type
     * @param \PSX\Api\Resource $resource
     * @return string
     */
    private function generateResource($type, Resource $resource)
    {
        switch ($type) {
            case 'php':
                $generator = new Generator\Php();
                return "<pre class='psx-out'><code class='php'>" . htmlspecialchars($generator->generate($resource)) . "</code></pre>";
                break;

            case 'raml':
                $generator = new Generator\Raml($resource->getTitle(), 1, 'http://api.phpsx.org', 'urn:schema.phpsx.org#');
                return "<pre class='psx-out'><code class='yaml'>" . htmlspecialchars($generator->generate($resource)) . "</code></pre>";
                break;

            case 'swagger':
                $generator = new Generator\Swagger($this->annotationReader, 1, '/', 'urn:schema.phpsx.org#');
                return "<pre class='psx-out'><code class='json'>" . htmlspecialchars($generator->generate($resource)) . "</code></pre>";
                break;

            case 'openapi':
                $generator = new Generator\OpenAPI($this->annotationReader, 1, 'http://api.phpsx.org', 'urn:schema.phpsx.org#');
                return "<pre class='psx-out'><code class='json'>" . htmlspecialchars($generator->generate($resource)) . "</code></pre>";
                break;

            case 'html':
            default:
                $generator = new Generator\Html\Schema(new Html());
                return $generator->generate($resource);
                break;
        }
    }
}
