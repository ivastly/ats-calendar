<?php declare(strict_types=1);

namespace Src\Calendar\MsExchange\Business\Domain;

use jamesiarmes\PhpEws\Enumeration\RoutingType;
use jamesiarmes\PhpEws\Type\AttendeeType;
use jamesiarmes\PhpEws\Type\EmailAddressType;

class Team
{
	private $emailsToNames;

	public function __construct(array $emailsToNames)
	{
		$this->emailsToNames = $emailsToNames;
	}

	public function getAttendies(): array
	{
		$attendees = [];
		foreach ($this->emailsToNames as $email => $name)
		{
			$attendee                        = new AttendeeType();
			$attendee->Mailbox               = new EmailAddressType();
			$attendee->Mailbox->EmailAddress = $email;
			$attendee->Mailbox->Name         = $name;
			$attendee->Mailbox->RoutingType  = RoutingType::SMTP;
			$attendees []                    = $attendee;
		}

		return $attendees;
	}
}
