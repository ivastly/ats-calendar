<?php declare(strict_types=1);

namespace Src\Integration\Business\Service;

use Src\Ats\DataAccess\Api\AtsClient;
use Src\Calendar\DataAccess\Api\CalendarClient;

class AtsToCalendarExportService
{
	/** @var AtsClient */
	private $atsClient;

	/** @var CalendarClient */
	private $calendarClient;

	public function __construct(AtsClient $atsClient, CalendarClient $calendarClient)
	{
		$this->atsClient      = $atsClient;
		$this->calendarClient = $calendarClient;
	}

	public function doAll(): void
	{
		$atsEvents              = $this->atsClient->searchForVacationEvents();
		$existingCalendarEvents = $this->calendarClient->searchForVacationEvents();

		foreach (array_diff($atsEvents, $existingCalendarEvents) as $newEvent)
		{
			echo "New vacation event found: $newEvent\n";
			// $this->calendarClient->postVacationEvent($newEvent);
		}
	}
}
