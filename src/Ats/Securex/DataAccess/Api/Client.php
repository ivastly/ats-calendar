<?php declare(strict_types=1);

namespace Src\Ats\Securex\DataAccess\Api;

use DateTime;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;
use Src\Ats\DataAccess\Api\AtsClient;
use Src\Config;
use Src\Integration\Business\Domain\VacationEvent;

class Client implements AtsClient
{
	private const REGULAR_DAY  = 'regular';
	private const VACATION_DAY = 'vacation';

	/** @var Config */
	private $config;

	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	public function searchForVacationEvents(): array
	{
		$rawData = $this->getRawDataFromPuphpeteer();

		return $this->mapRawChromeResponseToVacationEvents($rawData);
	}

	private function getRawDataFromPuphpeteer(): array
	{
		$puppeteer = new Puppeteer();
		$browser   = $puppeteer->launch();
		$page      = $browser->newPage();
		$page->goto(
			'https://www.securexhrservices.eu/sap/public/bc/ur/eWS/customer/SCX/newlogInPageSCX.html?sap-client=100',
			[
				'waitUntil' => 'networkidle0',
			]
		);

		$login    = $this->config->getEmail();
		$password = $this->config->getSecurexPassword();
		$page->evaluate(
			JsFunction::createWithBody(
				<<<JS
$('div#user-input input').val('$login');
$('div#password-input input').val('$password');
JS
			)
		);
		$page->click('#loginButton');
		$page->waitForNavigation(
			[
				'waitUntil' => 'networkidle0',
			]
		);
		$page->click('#topNavigationBtn'); // menu hamburger
		$page->waitFor('#textSubMenu-WA_PT');
		$page->click('#textSubMenu-WA_PT'); // Time Management
		$page->waitForNavigation(
			[
				'waitUntil' => 'networkidle0',
			]
		);
		$page->click("button[title='Team Calendar']"); // Team Calendar
		$page->waitForSelector("button[title='Select All']");
		$page->waitFor(2000);

// click 'Select All'
		$page->evaluate(
			JsFunction::createWithBody(
				<<<JS
jQuery("button[title='Select All']").click();
JS
			)
		);

		$jsCode           = file_get_contents(__DIR__ . '/../../../../../js/collect_vacations.js');
		$weeksToVacations = [];
		for ($week = 0; $week < 8; ++$week)
		{
			$page->waitForSelector('img.applicationTeamCalendar_style_picture');
			$page->waitFor(2000);

			$weekToVacations  = $page->evaluate(JsFunction::createWithBody($jsCode));
			$weeksToVacations = array_merge($weeksToVacations, $weekToVacations);
			// click 'Next Week'
			$page->evaluate(
				JsFunction::createWithBody(
					<<<JS
	jQuery('#applicationTeamCalendar_postButton').click();
JS
				)
			);
		}

		$browser->close();

		return $weeksToVacations;
	}

	private function mapRawChromeResponseToVacationEvents(array $rawResponse): array
	{
		$vacationEvents = [];
		foreach ($rawResponse as $weekId => $weekRepresentation)
		{
			$monday = $this->getMonday($weekId);

			foreach ($weekRepresentation as $nameId => $weekSchedule)
			{
				$name = $this->getTeammateName($nameId);

				$vacationStartShift     = null;
				$previousDayWasVacation = false;
				foreach ($weekSchedule as $shiftFromMonday => $dayId)
				{
					if (!$previousDayWasVacation && $dayId === self::VACATION_DAY)
					{
						// 🌴 vacation starts
						$vacationStartShift     = $shiftFromMonday;
						$previousDayWasVacation = true;
					}

					if ($previousDayWasVacation && $dayId === self::REGULAR_DAY)
					{
						// 💻 vacation ends
						$vacationEvents[] = VacationEvent::createFromData(
							$name,
							(clone $monday)->modify("+$vacationStartShift days"),
							(clone $monday)->modify('+' . ($shiftFromMonday - 1) . ' days')
						);

						$previousDayWasVacation = false;
					}
				}

				if ($previousDayWasVacation)
				{
					// week ends, vacation is still active -> make an event from it
					$vacationEvents[] = VacationEvent::createFromData(
						$name,
						(clone $monday)->modify("+$vacationStartShift days"),
						(clone $monday)->modify("+4 days")
					);

					$previousDayWasVacation = false;
				}
			}
		}

		return $vacationEvents;
	}

	private function getMonday(string $weekId): DateTime
	{
		// "Mo 30.09.2019 - Su 06.10.2019"
		return DateTime::createFromFormat('d.m.Y', substr($weekId, 3, 10));
	}

	private function getTeammateName(string $nameId): string
	{
		// "JOHN DOE [80006411]"
		$securexName = trim(preg_replace('/\[[\d]+\]/', '', $nameId));

		return $this->config->getNameBySecurexName($securexName);
	}
}
