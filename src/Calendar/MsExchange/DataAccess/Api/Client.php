<?php declare(strict_types=1);

namespace Src\Calendar\MsExchange\DataAccess\Api;

use DateTime;
use Exception;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfAllItemsType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfAttendeesType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseFolderIdsType;
use jamesiarmes\PhpEws\Client as PhpEwsClient;
use jamesiarmes\PhpEws\Enumeration\BodyTypeType;
use jamesiarmes\PhpEws\Enumeration\CalendarItemCreateOrDeleteOperationType;
use jamesiarmes\PhpEws\Enumeration\DefaultShapeNamesType;
use jamesiarmes\PhpEws\Enumeration\DistinguishedFolderIdNameType;
use jamesiarmes\PhpEws\Enumeration\ResponseClassType;
use jamesiarmes\PhpEws\Request\CreateItemType;
use jamesiarmes\PhpEws\Request\FindItemType;
use jamesiarmes\PhpEws\Type\BodyType;
use jamesiarmes\PhpEws\Type\CalendarItemType;
use jamesiarmes\PhpEws\Type\CalendarViewType;
use jamesiarmes\PhpEws\Type\DistinguishedFolderIdType;
use jamesiarmes\PhpEws\Type\ItemResponseShapeType;
use Src\Calendar\Business\Domain\VacationEvent;
use Src\Calendar\DataAccess\Api\AbstractCalendarClient;
use Src\Calendar\MsExchange\Business\Domain\Team;
use Src\Config;

class Client extends AbstractCalendarClient
{
	/** @var PhpEwsClient */
	private $client;

	/** @var Team */
	private $team;

	public function __construct(Config $config)
	{
		$this->client =
			new PhpEwsClient(
				$config->getMsExchangeHost(),
				$config->getEmail(),
				$config->getPassword(),
				$config->getMsExchangeVersion()
			);

		$this->team = new Team($config->getTeam());
	}

	public function searchForVacationEvents(): array
	{
		$startDate = new DateTime('now');
		$endDate   = new DateTime('+1 month');
		$timezone  = 'E. Europe Standard Time';
		$this->client->setTimezone($timezone);
		$request                                           = new FindItemType();
		$request->ParentFolderIds                          = new NonEmptyArrayOfBaseFolderIdsType();
		$request->ItemShape                                = new ItemResponseShapeType();
		$request->ItemShape->BaseShape                     = DefaultShapeNamesType::ALL_PROPERTIES;
		$folder_id                                         = new DistinguishedFolderIdType();
		$folder_id->Id                                     = DistinguishedFolderIdNameType::CALENDAR;
		$request->ParentFolderIds->DistinguishedFolderId[] = $folder_id;
		$request->CalendarView                             = new CalendarViewType();
		$request->CalendarView->StartDate                  = $startDate->format('c');
		$request->CalendarView->EndDate                    = $endDate->format('c');
		$response                                          = $this->client->FindItem($request);

		$responseMessages = $response->ResponseMessages->FindItemResponseMessage;
		foreach ($responseMessages as $response_message)
		{
			if ($response_message->ResponseClass != ResponseClassType::SUCCESS)
			{
				$code         = $response_message->ResponseCode;
				$message      = $response_message->MessageText;
				$errorMessage = "Failed to search for calendar events: $code: $message\n";
				fwrite(STDERR, $errorMessage);
				throw new Exception($errorMessage);
			}

			$items          = $response_message->RootFolder->Items->CalendarItem;
			$vacationEvents = [];
			foreach ($items as $item)
			{
				$title = $item->Subject;

				if (VacationEvent::isEventVacationByTitle($title))
				{
					$vacationEvents[] = VacationEvent::createFromCalendarTitle($title);
				}
			}

			return $vacationEvents;
		}
	}

	public function postVacationEvent(VacationEvent $vacationEvent): void
	{
		$request                         = new CreateItemType();
		$request->SendMeetingInvitations = CalendarItemCreateOrDeleteOperationType::SEND_ONLY_TO_ALL;
		$request->Items                  = new NonEmptyArrayOfAllItemsType();

		$event                              = new CalendarItemType();
		$event->RequiredAttendees           = new NonEmptyArrayOfAttendeesType();
		$event->Start                       = $vacationEvent->getStartDate()->format('c');
		$event->End                         = $vacationEvent->getEndDate()->format('c');
		$event->Subject                     = $vacationEvent->getVacationEventTitle();
		$event->Body                        = new BodyType();
		$event->Body->_                     = $this->getEventBody($vacationEvent);
		$event->Body->BodyType              = BodyTypeType::TEXT;
		$event->RequiredAttendees->Attendee = $this->team->getAttendies();

		$request->Items->CalendarItem[] = $event;
		$response                       = $this->client->CreateItem($request);

		$responseMessages = $response->ResponseMessages->CreateItemResponseMessage;
		foreach ($responseMessages as $responseMessage)
		{
			if ($responseMessage->ResponseClass != ResponseClassType::SUCCESS)
			{
				$code         = $responseMessage->ResponseCode;
				$message      = $responseMessage->MessageText;
				$errorMessage = "Failed to create calendar event: $code: $message\n";
				fwrite(STDERR, $errorMessage);
				throw new Exception($errorMessage);
			}
		}
	}

	private function getEventBody(VacationEvent $vacationEvent): string
	{
		$pluralSuffix = '';
		if ($vacationEvent->getTotalDays() > 1)
		{
			$pluralSuffix = 's';
		}

		return "Vacation of our colleague {$vacationEvent->getTeammate()} for {$vacationEvent->getTotalDays()} day$pluralSuffix";
	}
}
