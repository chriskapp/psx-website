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

    // GIT repo data
    'git_api'                 => 'https://api.github.com',
    'git_owner'               => 'apioo',
    'git_repo'                => 'psx',

    // The url to the psx public folder (i.e. http://127.0.0.1/psx/public or 
    // http://localhost.com)
    'psx_url'                 => 'http://127.0.0.1/websites/phpsx.org/public',

    // The input path 'index.php/' or '' if you use mod_rewrite
    'psx_dispatch'            => 'index.php/',

    // The default timezone
    'psx_timezone'            => 'UTC',

    // Whether PSX runs in debug mode or not. If not error reporting is set to 0
    'psx_debug'               => true,

    // Your SQL connections
    'psx_connection'          => [
        'path'                => __DIR__ . '/cache/blog.db',
        'driver'              => 'pdo_sqlite',
    ],

    // Path to the routing file
    'psx_routing'             => __DIR__ . '/routes',

    // Path to the cache folder
    'psx_path_cache'          => __DIR__ . '/cache',

    // Path to the library folder
    'psx_path_library'        => __DIR__ . '/src',

    // Class name of the error controller
    //'psx_error_controller'    => null,

    // If you only want to change the appearance of the error page you can 
    // specify a custom template
    //'psx_error_template'      => null,

);
