<?php

namespace Src\Ats\DataAccess\Api;

use Src\Integration\Business\Domain\VacationEvent;

interface AtsClient
{
	public function searchForVacationEvents(): VacationEvent;
}
