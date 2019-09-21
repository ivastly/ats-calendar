<?php declare(strict_types=1);

use Outlook\Authorizer\Authenticator;
use Outlook\Authorizer\Session;
use Outlook\Authorizer\Token;
use Outlook\Calendars\CalendarAuthorizer;
use Outlook\Calendars\CalendarManager;

require_once 'vendor/autoload.php';

$redirectUri = 'https://hurma.tv/azure/sync.php';

$sessionManager = new Session();
$authenticator  = new Authenticator(getenv(APP_ID), getenv(APP_PASSWORD), $redirectUri);
$authenticator->setSessionManager($sessionManager);

// this script has to be
$token = $authenticator->getToken();

if (!$token)
{
	//if $token is not available then we get login url
	$url = $authenticator->getLoginUrl($scopes = [], $redirectUri);
	echo 'no token, login url = ' . "<a href='$url'>login</a>";
	exit;
}
// if $token is available it is instance of Outlook\Authorizer\Token
echo "got token  \n"; //. var_dump($token);

$calenderAuthorizer = new CalendarAuthorizer($authenticator, $sessionManager);

$calendarToken = $calenderAuthorizer->isAuthenticated();

if (!$calendarToken)
{
	$url = $calenderAuthorizer->getLoginUrl($redirectUri, []);
	echo "need to get calendar token: " . "<a href='$url'>login</a>";
	exit;
}

$calendarManager = new CalendarManager($token);

var_dump($calendarManager->getAllCalendars());




