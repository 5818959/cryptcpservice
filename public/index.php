<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request = new \CryptCPService\Request();
    $request->handle($_POST);

    if ($request->validate()) {
        echo '<pre>';

        echo 'Valid request' . PHP_EOL;
        $service = new \CryptCPService\Service(CRYPTCP_PATH);
        $result = $service->verify($request);

        echo 'Request result: ' . ($result === true ? 'success' : 'fail') . PHP_EOL;
        echo PHP_EOL . 'Details: ' . PHP_EOL;
        var_dump($service->getLastOutput());

        echo '</pre>';
        // include __DIR__ . '/../views/result.tpl';
    } else {
        header('HTTP/1.0 400 Bad Request');
    }
} else {
    include __DIR__ . '/../views/index.tpl';
}
