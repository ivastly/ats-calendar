<?php

namespace Src\Ats\DataAccess\Api;

use Src\Integration\Business\Domain\VacationEvent;

interface AtsClient
{
	/**
	 * @return VacationEvent[]
	 */
	public function searchForVacationEvents(): array;
}
