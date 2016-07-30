<?php

define('DEBUG', false);

$cwd      = getcwd();
$basePath = __DIR__ . '/..';
$projects = json_decode(file_get_contents($basePath . '/projects.json'));
$result   = array_merge((array) $projects->primary, (array) $projects->secondary);

foreach ($result as $project => $row) {

    echo '-- Build ' . $project . "\n";

    // remove existing
    $apiDir = $basePath . '/public/api/' . $project;
    if (is_dir($apiDir)) {
        executeCmd('rm -rf ' . $apiDir);
    }

    $coverageDir = $basePath . '/public/coverage/' . $project;
    if (is_dir($coverageDir)) {
        executeCmd('rm -rf ' . $coverageDir);
    }

    // clone repo
    $gitDir = $basePath . '/build/' . $project;
    if (is_dir($gitDir)) {
        executeCmd('git clone ' . $row->git . ' ' . $gitDir);
    } else {
        executeCmd('cd ' . $gitDir . ' && git pull');
    }

    clearstatcache();

    if (is_dir($gitDir)) {
        // install composer
        chdir($gitDir);

        executeCmd('composer install');

        // generate api doc
        executeCmd('apigen generate --source ' . $basePath . '/build/' . $project . '/src --destination ' . $basePath . '/public/api/' . $project);

        // generate code coverage
        //executeCmd('vendor/bin/phpunit --coverage-html ' . $basePath . '/public/coverage/' . $project);

        chdir($cwd);

        echo 'Done!' . "\n\n";
    } else {
        echo 'Could not clone ' . $row->git . ' into ' . $gitDir . "\n\n";
    }
}

function executeCmd($cmd)
{
    echo '> ' . $cmd . "\n";

    if (DEBUG === false) {
        echo shell_exec($cmd) . "\n";
    }
}
