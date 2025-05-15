<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $class = ltrim($class, '\\');

    $baseDir = __DIR__ . '/www/';
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
        echo "File not found: $file";
    }
});