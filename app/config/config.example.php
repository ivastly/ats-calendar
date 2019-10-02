<?php declare(strict_types=1);

use jamesiarmes\PhpEws\Client;

return [
	'email'               => 'developer@company.com',
	'plain_text_password' => 'secret', # if null, the password will be asked for when the script is run
	'team'                => [
		'developer@company.com' => 'Developer',
		'master@company.com'    => 'Scrum Master',
		'teamlead@company.com'  => 'Team Lead',
	],
	'ms_exchange_host'    => 'webmail.company.com',
	'ms_exchange_version' => Client::VERSION_2016, // must be a constant from vendor/php-ews/php-ews/src/Client.php
];
