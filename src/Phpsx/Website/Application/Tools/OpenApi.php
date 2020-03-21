<?php

namespace Phpsx\Website\Application\Tools;

use PSX\Api\GeneratorFactory;
use PSX\Api\Resource;
use PSX\Framework\Annotation\ReaderFactory;
use PSX\Framework\Controller\ViewAbstract;
use PSX\Api\Parser;
use PSX\Api\Generator;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;
use PSX\Http\Writer\File;
use PSX\Schema\Generator\Code\Chunks;
use PSX\Schema\Generator\Html;
use PSX\Schema\Parser\JsonSchema\RefResolver;
use Symfony\Component\Yaml\Yaml;

class OpenApi extends ViewAbstract
{
    /**
     * @Inject
     * @var ReaderFactory
     */
    protected $annotationReaderFactory;

    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        $data = [
            'types' => GeneratorFactory::getPossibleTypes(),
        ];

        $this->render($response, __DIR__ . '/../../Resource/tools/open_api.html', $data);
    }

    public function onPost(RequestInterface $request, ResponseInterface $response)
    {
        $body = $this->requestReader->getBody($request);
        $type = isset($body->type) ? $body->type : 'html';
        $data = isset($body->data) ? $body->data : '';

        try {
            // parse openapi
            $resolver  = new RefResolver();
            $parser    = new Parser\OpenAPI(null, $resolver);
            $resources = $parser->parseAll(json_encode(Yaml::parse($data)));

            // generate output
            $factory   = new GeneratorFactory($this->annotationReaderFactory->factory('PSX\Schema\Parser\Popo\Annotation'), 'urn:schema.phpsx.org#', 'http://localhost', '');
            $generator = $factory->getGenerator($type);
            $result    = $generator->generateAll($resources);

            if (is_string($result)) {
                $result = "<pre class='psx-out'><code class='" . $type . "'>" . htmlspecialchars($result) . "</code></pre>";
            } elseif ($result instanceof Chunks) {
                $key  = substr(sha1($data), 0, 8);
                $name = 'sdk-' . $type .  '-' . $key . '.zip';
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

        $this->render($response, __DIR__ . '/../../Resource/tools/open_api.html', $data);
    }
}
