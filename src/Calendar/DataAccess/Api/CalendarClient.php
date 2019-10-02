<?php

namespace Src\Calendar\DataAccess\Api;

use Src\Integration\Business\Domain\VacationEvent;

interface CalendarClient
{
	/**
	 * @return VacationEvent[]
	 */
	public function searchForVacationEvents(): array;

	public function postVacationEvent(VacationEvent $event): void;
}
