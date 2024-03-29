<?php declare(strict_types=1);

namespace Src\Integration\Business\Domain;

use DateTime;
use Exception;

class VacationEvent
{
	const DATE_FORMAT = 'd M';

	/** @var string */
	private $teammate;

	/** @var DateTime */
	private $startDate;

	/** @var DateTime */
	private $endDate;

	private function __construct()
	{
	}

	public static function createFromData(string $teammate, DateTime $startDate, DateTime $endDate): self
	{
		$event            = new self();
		$event->teammate  = trim($teammate);
		$event->startDate = (clone $startDate)->setTime(10, 10);
		$event->endDate   = (clone $endDate)->setTime(10, 10);

		return $event;
	}

	public static function createFromCalendarTitle(string $title): self
	{
		if (!self::isEventVacationByTitle($title))
		{
			throw new Exception("Event title is not vacation: $title");
		}

		$matches = [];
		// 🌴 Vacation - John Doe - (02 Oct - 02 Oct)
		preg_match('/Vacation - ([^(]+) - \(([\d]{2} [A-z]{3}) - ([\d]{2} [A-z]{3})\)/', $title, $matches);

		return self::createFromData(
			$matches[1],
			DateTime::createFromFormat(self::DATE_FORMAT, $matches[2]),
			DateTime::createFromFormat(self::DATE_FORMAT, $matches[3])
		);
	}

	public function getStartDate(): DateTime
	{
		return $this->startDate;
	}

	public function getEndDate(): DateTime
	{
		return $this->endDate;
	}

	public function getTeammate(): string
	{
		return $this->teammate;
	}

	public function getTotalDays(): int
	{
		return $this->endDate->diff($this->startDate)->days + 1;
	}

	public static function isEventVacationByTitle(string $eventTitle): bool
	{
		return (bool)preg_match('/Vacation -/', $eventTitle);
	}

	public function getVacationEventTitle(): string
	{
		$from = $this->startDate->format(self::DATE_FORMAT);
		$to   = $this->endDate->format(self::DATE_FORMAT);

		return "🌴 Vacation - {$this->teammate} - ($from - $to)";
	}

	public function startsToday(): bool
	{
		return $this->startDate->diff(new DateTime('now'))->days === 0;
	}

	public function __toString()
	{
		return $this->getVacationEventTitle();
	}
}
