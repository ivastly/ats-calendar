<?php

namespace Src\Calendar\DataAccess\Api;

use Src\Calendar\Business\Domain\VacationEvent;

abstract class AbstractCalendarClient
{
	/**
	 * @return VacationEvent[]
	 */
	public abstract function searchForVacationEvents(): array;

	public abstract function postVacationEvent(VacationEvent $event): void;
}
