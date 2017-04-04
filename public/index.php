<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request = new \CryptCPService\Request();
    $request->handle($_POST);

    if ($request->validate()) {
        try {
            $service = new \CryptCPService\Service(CRYPTCP_PATH);
            $verifyResult = $service->verify($request);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            include __DIR__ . '/../views/error.tpl';
            exit;
        }
        $verifyDetails = implode(PHP_EOL, $service->getLastOutput());

        include __DIR__ . '/../views/result.tpl';
    } else {
        header('HTTP/1.0 400 Bad Request');
    }
} else {
    include __DIR__ . '/../views/index.tpl';
}
