<?php

namespace Src\Calendar\DataAccess\Api;

interface CalendarClientInterface
{
	/**
	 * @return Event[]
	 */
	public function searchForVacationEvents(): array;
}
