<?php

namespace Phpsx\Website\Application;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;
use PSX\Sql;
use Phpsx\Website\Table;

class Download extends ViewAbstract
{
    /**
     * @Inject
     * @var \PSX\Sql\TableManager
     */
    protected $tableManager;

    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        $release = $this->tableManager->getTable(Table\Release::class)->getLatestRelease();

        $data = [
            'links' => [[
                'rel'         => 'composer',
                'title'       => 'Composer',
                'href'        => 'https://packagist.org/packages/psx/sample',
                'description' => 'Sample project which contains a basic API to get started with PSX',
            ], [
                'rel'         => 'vagrant',
                'title'       => 'Vagrant',
                'href'        => 'https://github.com/k42b3/psx-vagrant',
                'description' => 'Repository which contains a Vagrant-Box with the PSX sample project',
            ], [
                'rel'         => 'download',
                'title'       => $release['tagName'],
                'href'        => $release['htmlUrl'],
                'description' => 'Latest repository tag',
            ]]
        ];

        $this->render($response, __DIR__ . '/../Resource/download.html', $data);
    }
}
