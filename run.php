<?php

require_once __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config.php';

if(count($argv) < 3) {
    echo "Usage: php run.php [carrier] [json]\n";
    exit(1);
}

$carrier = $argv[1];
$contents = $argv[2];

if(realpath($contents) !== false) {
    $body = json_decode(file_get_contents(realpath($contents)), true);
} else {
    $body = json_decode($contents, true);
}

if(json_last_error() != JSON_ERROR_NONE) {
    echo 'Invalid JSON: ' . json_last_error_msg() . "\n";
    exit(1);
}

$shipper = Shipper\Shipper::create(ucfirst($carrier));
$shipper->setApiKey($config[$carrier]);

$rateService = $shipper->rates();

$result = $rateService->fetch($body);

echo var_export($result->getTotal(), true) . "\n";