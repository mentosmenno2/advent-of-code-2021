<?php

class LanternFish
{
	/** @var int */
	protected $internal_timer;

	public function __construct(int $internal_timer = 8)
	{
		$this->internal_timer = $internal_timer;
	}

	public function get_internal_timer(): int
	{
		return $this->internal_timer;
	}

	public function set_internal_timer(int $internal_timer): void
	{
		$this->internal_timer = $internal_timer;
	}

	public function pass_internal_timer_day(): void
	{
		$this->internal_timer--;
		if ($this->internal_timer < 0) {
			$this->internal_timer = 6;
		}
	}

	public function can_give_birth(): bool
	{
		return $this->internal_timer === 0;
	}
}
