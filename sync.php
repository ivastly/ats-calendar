<?php declare(strict_types=1);

use Src\Calendar\Business\Domain\VacationEvent;
use Src\Calendar\MsExchange\DataAccess\Api\Client;
use Src\Config;

require_once 'vendor/autoload.php';

$config         = new Config(require_once __DIR__ . '/app/config/config.php');
$exchangeClient = new Client($config);

if (true)
{
	$vacation = VacationEvent::createFromData('Vasily Pyatykh', new DateTime('now'), new DateTime('+1 hour'));
	$exchangeClient->postVacationEvent($vacation);
	echo 'done';
	exit;
}

$vacationEvents = $exchangeClient->searchForVacationEvents();

if ($vacationEvents)
{
	echo "Events found:\n";
	foreach ($vacationEvents as $event)
	{
		echo "$event\n";
	}
} else
{
	echo "not found\n";
}
