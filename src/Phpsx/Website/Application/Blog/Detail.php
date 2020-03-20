<?php

namespace Phpsx\Website\Application\Blog;

use Phpsx\Website\Table;
use PSX\Framework\Controller\ViewAbstract;
use PSX\Http\Exception as StatusCode;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

class Detail extends ViewAbstract
{
    /**
     * @Inject
     * @var \PSX\Sql\TableManager
     */
    protected $tableManager;

    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        /** @var Table\Blog $table */
        $table = $this->tableManager->getTable(Table\Blog::class);
        $entry = $table->getOneByTitleSlug($this->context->getParameter('title'));

        if (empty($entry)) {
            throw new StatusCode\NotFoundException('Entry not found');
        }

        $this->render($response, __DIR__ . '/../../Resource/blog/detail.html', $entry);
    }
}
