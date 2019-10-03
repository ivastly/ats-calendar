<?php declare(strict_types=1);

namespace Src;

class Config
{
	/** @var string */
	private $email;

	/** @var string */
	private $exchangePassword;

	/** @var string */
	private $securexPassword;

	/** @var array */
	private $team;

	/** @var string */
	private $msExchangeHost;

	/** @var string */
	private $msExchangeVersion;

	public function __construct(array $config)
	{
		$this->email            = $config['email'];
		$this->exchangePassword = $config['exchange_password'];
		if (!$this->exchangePassword)
		{
			$this->exchangePassword = 'stdin'; // TODO read from STDIN
		}
		$this->securexPassword = $config['securex_password'];
		if (!$this->securexPassword)
		{
			$this->securexPassword = 'stdin'; // TODO read from STDIN
		}
		$this->team              = $config['team'];
		$this->msExchangeHost    = $config['ms_exchange_host'];
		$this->msExchangeVersion = $config['ms_exchange_version'];
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getExchangepassword(): string
	{
		return $this->exchangePassword;
	}

	public function getTeam(): array
	{
		return $this->team;
	}

	public function getMsExchangeHost(): string
	{
		return $this->msExchangeHost;
	}

	public function getMsExchangeVersion(): string
	{
		return $this->msExchangeVersion;
	}
}
