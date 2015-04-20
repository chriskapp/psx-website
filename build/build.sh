#!/bin/sh
base_path='/var/www/phpsx.org'
rm -rf $base_path/build/psx
rm -rf $base_path/public/api/*
rm -rf $base_path/public/coverage/*
git clone https://github.com/k42b3/psx.git $base_path/build/psx
cd $base_path/build/psx
composer install
composer require doctrine/orm ~2.4
composer require twig/twig ~1.16
composer require phpunit/phpunit ~4.1
composer require phpunit/dbunit ~1.3
apigen --source $base_path/build/psx/library --destination $base_path/public/api
timeout 600 php -S 127.0.0.1:8000 tests/PSX/Http/Server.php &
server_pid=$!
sleep 4
php $base_path/build/psx/vendor/bin/phpunit --coverage-html $base_path/public/coverage
kill $server_pid
sphinx-build $base_path/build/psx/doc $base_path/public/doc
