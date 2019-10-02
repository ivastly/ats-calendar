<?php declare(strict_types=1);

use Src\Calendar\MsExchange\DataAccess\Api\Client;
use Src\Config;

require_once 'vendor/autoload.php';

$config         = new Config(require_once __DIR__ . '/app/config/config.php');
$exchangeClient = new Client($config);
