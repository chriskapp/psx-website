<?php

namespace Phpsx\Website\Application\Schema;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Schema\Generator as SchemaGenerator;
use PSX\Schema\Parser\JsonSchema;

class Generator extends ViewAbstract
{
    public function doIndex()
    {
    }
    
    public function doGenerate()
    {
        $body = $this->getBody();
        $type = isset($body->type) ? $body->type : 'html';
        $data = isset($body->data) ? $body->data : '';

        // parse json schema
        $resolver = new JsonSchema\RefResolver();
        $parser   = new JsonSchema(null, $resolver);
        $schema   = $parser->parse($data);
        
        // generate output
        switch ($type) {
            case 'php':
                $generator = new SchemaGenerator\Php();
                $result  = "<pre class='psx-out'><code class='php'>" . htmlspecialchars($generator->generate($schema)) . "</code></pre>";
                break;

            case 'html':
            default:
                $generator = new SchemaGenerator\Html();
                $result  = $generator->generate($schema);
                break;
        }

        $this->setBody([
            'in'  => htmlspecialchars($data),
            'out' => $result,
        ]);
    }
}
