<?php declare(strict_types=1);

namespace Src\Ats\Securex\DataAccess\Api;

use Src\Ats\DataAccess\Api\AtsClient;
use Src\Config;

class Client implements AtsClient
{
	public function __construct(Config $config)
	{
	}

	public function searchForVacationEvents(): array
	{
		return [];
	}
}
