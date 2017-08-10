<?php

namespace Phpsx\Website\Application\Tools;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Schema\Generator as SchemaGenerator;
use PSX\Schema\Parser;
use PSX\Schema\SchemaInterface;

class JsonSchema extends ViewAbstract
{
    public function doIndex()
    {
    }

    public function doGenerate()
    {
        $body = $this->getBody();
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

        $this->setBody([
            'in'  => htmlspecialchars($data),
            'out' => $result,
        ]);
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
