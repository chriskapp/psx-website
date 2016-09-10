<?php

namespace Phpsx\Website\Application;

use PSX\Framework\Controller\ViewAbstract;
use PSX\Record\RecordInterface;
use PSX\Http\Exception as StatusCode;
use PSX\Http\Stream\TempStream;
use PSX\Sql\Condition;

class Blog extends ViewAbstract
{
	const ITEMS_PER_PAGE = 8;

	/**
	 * @Inject
	 * @var \PSX\Sql\TableManager
	 */
	protected $tableManager;

	/**
	 * @Inject
	 * @var \PSX\Framework\Loader\ReverseRouter
	 */
	protected $reverseRouter;

	public function doIndex()
	{
		$table        = $this->tableManager->getTable('Phpsx\Website\Table\Blog');
		$totalResults = $table->getCount();
		$selfUrl      = $this->reverseRouter->getUrl(__METHOD__);
		$startIndex   = $this->getStartIndex();

		$this->setBody([
			'totalResults' => $totalResults,
			'startIndex'   => $startIndex,
			'entry'        => $table->getIndexEntries($startIndex),
			'links'        => $this->getLinks($selfUrl, $startIndex, $totalResults),
		]);
	}

	public function doDetail()
	{
		$table = $this->tableManager->getTable('Phpsx\Website\Table\Blog');
		$entry = $table->getOneByTitleSlug($this->getUriFragment('title'));

		if($entry instanceof RecordInterface)
		{
			$this->template->set('blog_detail.html');

			$this->setBody($entry);
		}
		else
		{
			throw new StatusCode\NotFoundException('Entry not found');
		}
	}

	public function doCategory()
	{
		$table        = $this->tableManager->getTable('Phpsx\Website\Table\Blog');
		$category     = $this->getUriFragment('category');
		$totalResults = $table->getCount(new Condition(['category', 'LIKE', '%' . $category . '%']));

		if($totalResults > 0)
		{
			$selfUrl      = $this->reverseRouter->getUrl(__METHOD__, ['category' => $category]);
			$startIndex   = $this->getStartIndex();

			$this->setBody([
				'totalResults' => $totalResults,
				'startIndex'   => $startIndex,
				'entry'        => $table->getEntriesByCategory($category, $startIndex),
				'links'        => $this->getLinks($selfUrl, $startIndex, $totalResults),
			]);
		}
		else
		{
			throw new StatusCode\NotFoundException('Category does not exist');
		}
	}

	public function doFeed()
	{
		$this->setHeader('Content-Type', 'application/atom+xml');
		$this->setBody(new TempStream(fopen($this->config['blog_file'], 'r')));
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
	protected function getStartIndex()
	{
		$startIndex = (int) $this->getParameter('startIndex');
		$startIndex = $startIndex < 0 ? 0 : $startIndex;
		$startIndex = $startIndex % self::ITEMS_PER_PAGE !== 0 ? $startIndex - ($startIndex % self::ITEMS_PER_PAGE) : $startIndex;

		return $startIndex;
	}
}
