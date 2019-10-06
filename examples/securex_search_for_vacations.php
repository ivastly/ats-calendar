<?php declare(strict_types=1);

use Src\Ats\Securex\DataAccess\Api\Client;
use Src\Config;

require_once __DIR__ . '/../vendor/autoload.php';

$config        = new Config(require_once __DIR__ . '/../app/config/config.php');
$securexClient = new Client($config);

// run for about 10-20 seconds
$events = $securexClient->searchForVacationEvents();
if ($events)
{
	echo "Vacations found:\n";
	foreach ($events as $event)
	{
		echo "$event\n";
	}
} else
{
	echo "Nothing found\n";
}
