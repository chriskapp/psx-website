<?php

namespace Phpsx\Website\Application;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Http\Exception as StatusCode;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;
use PSX\Http\Stream\TempStream;
use PSX\Record\RecordInterface;
use PSX\Sql\Condition;
use Phpsx\Website\Table;

class Blog extends ViewAbstract
{
    const ITEMS_PER_PAGE = 8;

    /**
     * @Inject
     * @var \PSX\Sql\TableManager
     */
    protected $tableManager;

    public function onGet(RequestInterface $request, ResponseInterface $response)
    {
        $table    = $this->tableManager->getTable(Table\Blog::class);
        $category = $this->context->getParameter('category');

        $condition = null;
        if (empty($category)) {
            $condition = new Condition(['category', 'LIKE', '%' . $category . '%']);
        }

        $totalResults = $table->getCount($condition);
        $startIndex   = $this->getStartIndex((int) $request->getUri()->getParameter('startIndex'));

        if (empty($category)) {
            $entries = $table->getIndexEntries($startIndex);
            $selfUrl = $this->reverseRouter->getUrl(__METHOD__);
        } else {
            $entries = $table->getEntriesByCategory($category, $startIndex);
            $selfUrl = $this->reverseRouter->getUrl(__METHOD__, ['category' => $category]);
        }

        $data = [
            'totalResults' => $totalResults,
            'startIndex'   => $startIndex,
            'entry'        => $entries,
            'links'        => $this->getLinks($selfUrl, $startIndex, $totalResults),
        ];

        $this->render($response, __DIR__ . '/../Resource/blog.html', $data);
    }

    /**
     * Returns the HATEOAS links for further navigation
     *
     * @return array
     */
    protected function getLinks($selfUrl, $startIndex, $totalResults)
    {
        $prev = $startIndex - self::ITEMS_PER_PAGE;
        $prev = $prev < 0 ? 0 : $prev;
        $next = $startIndex + self::ITEMS_PER_PAGE;
        $next = $next >= $totalResults ? $startIndex : $next;

        return [[
            'rel'  => 'self',
            'href' => $selfUrl,
        ],[
            'rel'  => 'next',
            'href' => $selfUrl . '?startIndex=' . $next,
        ],[
            'rel'  => 'prev',
            'href' => $selfUrl . '?startIndex=' . $prev,
        ]];
    }

    /**
     * Returns the startIndex GET parameter
     *
     * @return integer
     */
    protected function getStartIndex($startIndex)
    {
        $startIndex = $startIndex < 0 ? 0 : $startIndex;
        $startIndex = $startIndex % self::ITEMS_PER_PAGE !== 0 ? $startIndex - ($startIndex % self::ITEMS_PER_PAGE) : $startIndex;

        return $startIndex;
    }
}
