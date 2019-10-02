<?php declare(strict_types=1);

namespace Src;

class Config
{
	/** @var string */
	private $email;

	/** @var string */
	private $password;

	/** @var array */
	private $team;

	/** @var string */
	private $msExchangeHost;

	/** @var string */
	private $msExchangeVersion;

	public function __construct(array $config)
	{
		$this->email             = $config['email'];
		$this->password          = $config['plain_text_password'];
		if (!$this->password)
		{
			$this->password = 'stdin'; // TODO read from STDIN
		}
		$this->team              = $config['team'];
		$this->msExchangeHost    = $config['ms_exchange_host'];
		$this->msExchangeVersion = $config['ms_exchange_version'];
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getPassword(): string
	{
		return $this->password;
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
