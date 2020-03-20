<?php

return [
    [['GET'], '/',                         Phpsx\Website\Application\Index::class],
    [['GET'], '/humans.txt',               Phpsx\Website\Application\Humans::class],
    [['GET'], '/about',                    Phpsx\Website\Application\About::class],
    [['GET'], '/get-started',              Phpsx\Website\Application\Bootstrap::class],
    [['GET'], '/download',                 Phpsx\Website\Application\Download::class],
    [['GET'], '/components',               Phpsx\Website\Application\Components::class],
    [['GET'], '/documentation',            Phpsx\Website\Application\Documentation::class],
    [['GET'], '/tools',                    Phpsx\Website\Application\Tools::class],
    [['GET'], '/security',                 Phpsx\Website\Application\Security::class],
    [['GET'], '/imprint',                  Phpsx\Website\Application\Imprint::class],
    [['GET'], '/disclosure',               Phpsx\Website\Application\Disclosure::class],
    [['GET'], '/blog',                     Phpsx\Website\Application\Blog::class],
    [['GET'], '/blog/category/:category',  Phpsx\Website\Application\Blog::class],
    [['GET'], '/blog/post/:title',         Phpsx\Website\Application\Blog\Detail::class],
    [['GET'], '/blog/feed',                Phpsx\Website\Application\Blog\Feed::class],

    # tools
    [['GET', 'POST'], '/tools/jsonschema', Phpsx\Website\Application\Tools\JsonSchema::class],
    [['GET', 'POST'], '/tools/openapi',    Phpsx\Website\Application\Tools\OpenApi::class],

    # tutorials
    [['GET'], '/create-a-rest-api-from-mysql', Phpsx\Website\Application\Documentation\TutorialMysql::class]
];

