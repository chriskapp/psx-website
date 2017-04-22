<?php

namespace Phpsx\Website\Application\Schema;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Json\Parser;
use PSX\Schema\Generator;
use PSX\Schema\Parser\JsonSchema;

class Editor extends ViewAbstract
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
                $generator = new Generator\Php();
                $result  = "<pre class='psx-out'><code class='php'>" . htmlspecialchars($generator->generate($schema)) . "</code></pre>";
                break;

            case 'html':
            default:
                $generator = new Generator\Html();
                $result  = $generator->generate($schema);
                break;
        }

        $this->setBody([
            'in'  => htmlspecialchars($data),
            'out' => $result,
        ]);
    }
}
