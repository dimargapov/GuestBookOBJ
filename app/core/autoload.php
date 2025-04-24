<?php
spl_autoload_register(function ($className) {
    $paths = [
//        __DIR__ . '/../models/',
//        __DIR__ . '/../controllers/',
//        __DIR__ . '/../core/',
        __DIR__ . '/../',
    ];

    foreach ($paths as $path) {
        // Заменяем namespace на путь
        $file = $path . str_replace('\\', '/', $className) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});