<?php declare(strict_types=1);

use jamesiarmes\PhpEws\Client;

return [
	'email'                       => 'developer@company.com',

	// if null, the password will be asked for when the script is run
	'exchange_password'           => null,

	// if null, the password will be asked for when the script is run
	'securex_password'            => null,
	'securex_names_to_team_names' => [
		'DEVELOPER FULL NAME'    => 'Developer',
		'SCRUM MASTER FULL NAME' => 'Scrum Master',
		'TEAM LEAD FULL NAME'    => 'Team Lead',
	],
	'team'                        => [
		'developer@company.com' => 'Developer',
		'master@company.com'    => 'Scrum Master',
		'teamlead@company.com'  => 'Team Lead',
	],
	'ms_exchange_host'            => 'webmail.company.com',

	// must be a constant from vendor/php-ews/php-ews/src/Client.php
	'ms_exchange_version'         => Client::VERSION_2016,
];
