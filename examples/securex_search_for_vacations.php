<?php declare(strict_types=1);

use Nesk\Puphpeteer\Puppeteer;
use Src\Ats\Securex\DataAccess\Api\Client;
use Src\Config;

require_once __DIR__ . '/../vendor/autoload.php';

$config = new Config(require_once __DIR__ . '/../app/config/config.php');
$securexClient = new Client($config);

foreach ($securexClient->searchForVacationEvents() as $event)
{
	echo "Vacations found: $event\n";
}

$puppeteer = new Puppeteer();
$browser   = $puppeteer->launch();
$page      = $browser->newPage();
$page->$page->goto(
	'https://www.securexhrservices.eu/sap/public/bc/ur/eWS/customer/SCX/newlogInPageSCX.html?sap-client=100'
);
$page->screenshot(['path' => 'example.png']);

$browser->close();

echo 'done';
