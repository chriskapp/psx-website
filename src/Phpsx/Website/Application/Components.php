<?php

namespace Phpsx\Website\Application;

use PSX\Framework\Controller\ViewAbstract;

class Components extends ViewAbstract
{
    public function doIndex()
    {
        $projects = json_decode(file_get_contents(__DIR__ . '/../../../../projects.json'));

        $this->setBody([
            'projects' => $projects->primary,
        ]);
    }
}
