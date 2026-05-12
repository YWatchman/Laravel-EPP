<?php

if (PHP_VERSION_ID < 80500) {
    exit(0);
}

$files = [
    __DIR__.'/../vendor/laravel/framework/config/database.php',
    __DIR__.'/../vendor/orchestra/testbench-core/laravel/config/database.php',
];

foreach ($files as $file) {
    if (! file_exists($file)) {
        continue;
    }

    file_put_contents($file, str_replace(
        'PDO::MYSQL_ATTR_SSL_CA',
        '(PHP_VERSION_ID >= 80500 ? Pdo\\Mysql::ATTR_SSL_CA : PDO::MYSQL_ATTR_SSL_CA)',
        file_get_contents($file),
    ));
}
