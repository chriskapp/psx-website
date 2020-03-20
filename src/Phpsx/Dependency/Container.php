<?php

namespace Phpsx\Dependency;

use Phpsx\Website\Console;
use Phpsx\Website\Table;
use PSX\Framework\Dependency\DefaultContainer;
use Symfony\Component\Console\Application;

class Container extends DefaultContainer
{
    protected function appendConsoleCommands(Application $application)
    {
        parent::appendConsoleCommands($application);

        $application->add(new Console\CreateSchemaCommand($this->get('connection')));
        $application->add(new Console\FetchReleaseCommand($this->get('table_manager')->getTable(Table\Release::class), $this->get('http_client'), $this->get('config')));
        $application->add(new Console\PackageBlogPostCommand($this->get('table_manager')->getTable(Table\Blog::class), $this->get('http_client'), $this->get('config')));
        $application->add(new Console\ShowReleaseCommand($this->get('table_manager')->getTable(Table\Release::class)));
        $application->add(new Console\UpdateBlogCommand($this->get('table_manager')->getTable(Table\Blog::class), $this->get('io'), $this->get('config')->get('blog_file')));
        $application->add(new Console\ShowBlogCommand($this->get('table_manager')->getTable(Table\Blog::class)));
    }
}
