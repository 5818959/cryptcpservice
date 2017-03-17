<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request = new \CryptCPService\Request();
    $request->handle($_POST);

    if ($request->validate()) {
        echo 'Valid request' . PHP_EOL;
    } else {
        header('HTTP/1.0 400 Bad Request');
    }
} else {
    include_once __DIR__ . '/../views/index.tpl';
}
