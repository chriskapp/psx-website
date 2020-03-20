<?php

/*
This is the configuration file of PSX. Every parameter can be used inside your
application or in the DI container. Which configuration file gets loaded depends 
on the DI container parameter "config.file". See the container.php if you want 
load an different configuration depending on the environment.
*/

return array(

    // File which contains an atom feed with blog entries
    'disclosure_file'         => __DIR__ . '/disclosure.xml',
    'blog_file'               => __DIR__ . '/blog.xml',
    'projects_file'           => __DIR__ . '/projects.json',

    // GIT repo data
    'git_api'                 => 'https://api.github.com',
    'git_owner'               => 'apioo',
    'git_repo'                => 'psx',

    // The url to the psx public folder (i.e. http://127.0.0.1/psx/public or 
    // http://localhost.com)
    'psx_url'                 => 'http://127.0.0.1/websites/phpsx.org/public',

    // To enable clean urls you need to set this to '' this works only in case
    // mod rewrite is activated
    'psx_dispatch'            => 'index.php/',

    // The default timezone
    'psx_timezone'            => 'UTC',

    // Whether PSX runs in debug mode or not. If not error reporting is set to 0
    // Also several caches are used if the debug mode is false
    'psx_debug'               => true,

    // Database parameters which are used for the doctrine DBAL connection
    // http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html
    'psx_connection'          => [
        'path'                => __DIR__ . '/cache/blog.db',
        'driver'              => 'pdo_sqlite',
    ],

    // Path to the routing file
    'psx_routing'             => __DIR__ . '/routes.php',

    // Folder locations
    'psx_path_cache'          => __DIR__ . '/cache',
    'psx_path_public'         => __DIR__ . '/public',
    'psx_path_src'            => __DIR__ . '/src',

    // Supported writers
    'psx_supported_writer'    => [
        \PSX\Data\Writer\Json::class,
        \PSX\Data\Writer\Jsonp::class,
        \PSX\Data\Writer\Jsonx::class,
    ],

    // Global middleware which are applied before and after every request. Must
    // bei either a classname, closure or PSX\Dispatch\FilterInterface instance
    //'psx_filter_pre'          => [],
    //'psx_filter_post'         => [],

    // A closure which returns a doctrine cache implementation. If null the
    // filesystem cache is used
    /*
    'psx_cache_factory'       => function($config, $namespace){
        $memcached = new \Memcached();
        $memcached->addServer(getenv('FUSIO_MEMCACHE_HOST'), getenv('FUSIO_MEMCACHE_PORT'));

        $memcache = new \Doctrine\Common\Cache\MemcachedCache();
        $memcache->setMemcached($memcached);
        $memcache->setNamespace($namespace);

        return $memcache;
    },
    */

    // Specify a specific log level
    //'psx_log_level' => \Monolog\Logger::ERROR,

    // A closure which returns a monolog handler implementation. If null the
    // system handler is used
    //'psx_logger_factory'      => null,

);
