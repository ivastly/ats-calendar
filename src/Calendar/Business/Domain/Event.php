<?php declare(strict_types=1);

namespace Src\Calendar\MsExchange\Business\Domain;

use DateTime;

class Event
{
	/** @var string */
	private $title;

	/** @var DateTime */
	private $startDate;

	/** @var DateTime */
	private $endDate;
}
