<?php declare(strict_types=1);

use Src\Config;
use Src\MsExchange\Client;

require_once 'vendor/autoload.php';

$config         = new Config(require_once __DIR__ . '/app/config/config.php');
$exchangeClient = new Client($config);

$exchangeClient->searchForVacationEvents();
