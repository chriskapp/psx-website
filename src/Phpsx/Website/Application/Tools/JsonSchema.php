<?php

namespace Phpsx\Website\Application\Tools;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;
use PSX\Schema\Generator as SchemaGenerator;
use PSX\Schema\GeneratorFactory;
use PSX\Schema\Parser;
use PSX\Schema\SchemaInterface;

class JsonSchema extends ViewAbstract
{
    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        $data = [
            'types' => GeneratorFactory::getPossibleTypes()
        ];
    
        $this->render($response, __DIR__ . '/../../Resource/tools/json_schema.html', $data);
    }

    public function onPost(RequestInterface $request, ResponseInterface $response)
    {
        $body = $this->requestReader->getBody($request);
        $type = isset($body->type) ? $body->type : 'html';
        $data = isset($body->data) ? $body->data : '';

        try {
            // parse json schema
            $resolver = new Parser\JsonSchema\RefResolver();
            $parser   = new Parser\JsonSchema(null, $resolver);
            $schema   = $parser->parse($data);

            // generate output
            $result = $this->generateSchema($type, $schema);
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        $data = [
            'types' => GeneratorFactory::getPossibleTypes(),
            'in'  => htmlspecialchars($data),
            'out' => $result,
        ];

        $this->render($response, __DIR__ . '/../../Resource/tools/json_schema.html', $data);
    }

    /**
     * @param $type
     * @param \PSX\Schema\SchemaInterface $schema
     * @return string
     */
    private function generateSchema($type, SchemaInterface $schema)
    {
        switch ($type) {
            case 'php':
                $generator = new SchemaGenerator\Php();
                return "<pre class='psx-out'><code class='php'>" . htmlspecialchars($generator->generate($schema)) . "</code></pre>";
                break;

            case 'html':
            default:
                $generator = new SchemaGenerator\Html();
                return $generator->generate($schema);
                break;
        }
    }
}
