<?php declare(strict_types=1);

namespace Src\MsExchange;

use DateTime;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseFolderIdsType;
use jamesiarmes\PhpEws\Client as PhpEwsClient;
use jamesiarmes\PhpEws\Enumeration\DefaultShapeNamesType;
use jamesiarmes\PhpEws\Enumeration\DistinguishedFolderIdNameType;
use jamesiarmes\PhpEws\Enumeration\ResponseClassType;
use jamesiarmes\PhpEws\Request\FindItemType;
use jamesiarmes\PhpEws\Type\CalendarViewType;
use jamesiarmes\PhpEws\Type\DistinguishedFolderIdType;
use jamesiarmes\PhpEws\Type\ItemResponseShapeType;
use Src\Calendar\DataAccess\Api\CalendarClientInterface;
use Src\Config;

class Client implements CalendarClientInterface
{
	/** @var PhpEwsClient */
	private $client;

	public function __construct(Config $config)
	{
		$this->client =
			new PhpEwsClient(
				$config->getMsExchangeHost(),
				$config->getEmail(),
				$config->getPassword(),
				$config->getMsExchangeVersion()
			);
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

// Iterate over the results, printing any error messages or event ids.
		$ResponseMessages = $response->ResponseMessages->FindItemResponseMessage;
		foreach ($ResponseMessages as $response_message)
		{
			if ($response_message->ResponseClass != ResponseClassType::SUCCESS)
			{
				$code    = $response_message->ResponseCode;
				$message = $response_message->MessageText;
				fwrite(
					STDERR,
					"Failed to search for events with \"$code: $message\"\n"
				);
				continue;
			}

			// Iterate over the events that were found, printing some data for each.
			$items = $response_message->RootFolder->Items->CalendarItem;
			foreach ($items as $item)
			{
				$id     = $item->ItemId->Id;
				$start  = new DateTime($item->Start);
				$end    = new DateTime($item->End);
				$output = 'Found event ' . $item->ItemId->Id . "\n"
					. '  Change Key: ' . $item->ItemId->ChangeKey . "\n"
					. '  Title: ' . $item->Subject . "\n"
					. '  Start: ' . $start->format('l, F jS, Y g:ia') . "\n"
					. '  End:   ' . $end->format('l, F jS, Y g:ia') . "\n\n";
				fwrite(STDOUT, $output);
			}
		}
	}
}
