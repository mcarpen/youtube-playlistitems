<?php
require __DIR__ . '/vendor/autoload.php';
require 'getVideoIds.php';
$params = require 'parameters.php';

$url = $_GET['url'];

$idsList = getVideoIds($url, $params['apiKey']);

echo json_encode($idsList);