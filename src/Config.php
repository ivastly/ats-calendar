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

	/** @var array */
	private $securexNamesToTeamNames;

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
			$this->exchangePassword = readline('Please provide MS Exchange password:');
		}
		$this->securexPassword = $config['securex_password'];
		if (!$this->securexPassword)
		{
			$this->securexPassword = readline('Please provide Securex password:');
		}
		$this->team                    = $config['team'];
		$this->securexNamesToTeamNames = $config['securex_names_to_team_names'];
		$this->msExchangeHost          = $config['ms_exchange_host'];
		$this->msExchangeVersion       = $config['ms_exchange_version'];
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getExchangePassword(): string
	{
		return $this->exchangePassword;
	}

	public function getSecurexPassword(): string
	{
		return $this->securexPassword;
	}

	public function getTeam(): array
	{
		return $this->team;
	}

	public function getNameBySecurexName(string $securexName): string
	{
		return $this->securexNamesToTeamNames[$securexName] ?? 'Not configured name';
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
