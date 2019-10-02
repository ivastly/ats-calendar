<?php declare(strict_types=1);

use Src\Calendar\MsExchange\DataAccess\Api\Client;
use Src\Config;
use Src\Integration\Business\Domain\VacationEvent;

require_once __DIR__ . '/../vendor/autoload.php';

$config         = new Config(require_once __DIR__ . '/../app/config/config.php');
$exchangeClient = new Client($config);

$vacation = VacationEvent::createFromData('John Doe', new DateTime('now'), new DateTime('+1 hour'));
$exchangeClient->postVacationEvent($vacation);

echo 'done';
