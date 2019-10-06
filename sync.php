<?php declare(strict_types=1);

use Src\Ats\Securex\DataAccess\Api\Client as SecurexClient;
use Src\Calendar\MsExchange\DataAccess\Api\Client as ExchangeClient;
use Src\Config;
use Src\Integration\Business\Service\AtsToCalendarExportService;

require_once 'vendor/autoload.php';

$config         = new Config(require_once __DIR__ . '/app/config/config.php');
$exchangeClient = new ExchangeClient($config);
$atsClient      = new SecurexClient($config);
$exportService  = new AtsToCalendarExportService($atsClient, $exchangeClient);

while (true)
{
	$exportService->doAll();
	sleep(60 * 60);
}
