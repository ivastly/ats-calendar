<?php declare(strict_types=1);

use Nesk\Puphpeteer\Puppeteer;

require_once 'vendor/autoload.php';

$puppeteer = new Puppeteer();
$browser   = $puppeteer->launch(
	[
		'args' =>
			[
				// this is required for dockerized puppeteer
				'--no-sandbox',
				'--disable-setuid-sandbox',
				'--disable-dev-shm-usage',
			],
	]
);
$page      = $browser->newPage();
$page->goto(
	'https://example.com',
	[
		'waitUntil' => 'networkidle0',
	]
);

var_dump($page->content());
