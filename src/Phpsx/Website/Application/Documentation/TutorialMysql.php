<?php

namespace Phpsx\Website\Application\Documentation;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

class TutorialMysql extends ViewAbstract
{
    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        $this->render($response, __DIR__ . '/../../Resource/documentation/tutorial_mysql.html', []);
    }
}
