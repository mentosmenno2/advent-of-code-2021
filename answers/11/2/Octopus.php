<?php

class Octopus
{
	/** @var int */
	protected $energy_level;

	/** @var bool */
	protected $flashed = false;

	public function __construct(int $energy_level)
	{
		$this->energy_level = $energy_level;
	}

	public function get_energy_level(): int
	{
		return $this->energy_level;
	}

	public function set_energy_level(int $energy_level): void
	{
		$this->energy_level = $energy_level;
	}

	public function get_flashed(): bool
	{
		return $this->flashed;
	}

	public function set_flashed(bool $flashed): void
	{
		$this->flashed = $flashed;
	}

	public function reset(): void
	{
		$this->energy_level = 0;
		$this->flashed = false;
	}
}
