<?php

namespace Phpsx\Website\Application\Tools;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;
use PSX\Http\Writer\File;
use PSX\Schema\Generator as SchemaGenerator;
use PSX\Schema\GeneratorFactory;
use PSX\Schema\Parser;

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
            $resolver  = new Parser\JsonSchema\RefResolver();
            $parser    = new Parser\JsonSchema(null, $resolver);
            $schema    = $parser->parse($data);

            // generate output
            $factory   = new GeneratorFactory();
            $generator = $factory->getGenerator($type, '');
            $result    = $generator->generate($schema);

            if (is_string($result)) {
                $result = "<pre class='psx-out'><code class='" . $type . "'>" . htmlspecialchars($result) . "</code></pre>";
            } elseif ($result instanceof SchemaGenerator\Code\Chunks) {
                $key  = substr(sha1($data), 0, 8);
                $name = 'schema-' . $type .  '-' . $key . '.zip';
                $file = PSX_PATH_CACHE . '/' . $name;
                if (!is_file($file)) {
                    $result->writeTo($file);
                }

                $this->responseWriter->setBody($response, new File($file, $name, 'application/zip'));
                return;
            } else {
                throw new \RuntimeException('Generator returned an invalid response');
            }
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
}
