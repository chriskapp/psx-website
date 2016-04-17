<?php

namespace Phpsx\Website\Application;

use PSX\Controller\ViewAbstract;

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
