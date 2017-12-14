<?php

namespace Tests\Unit;

use App\Setting;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsTest extends TestCase
{
	use RefreshDatabase;

	public function setUp()
	{
		parent::setUp();
		$this->seed('SettingsTableSeeder');

		$this->settings = new Setting;
	}

	/**
	 * @test
	 * it can keep track of hands position
	 */
	public function it_can_keep_track_of_hands_position()
	{
		$this->settings->track('primary_hand', 50);
		$this->assertDatabaseHas('settings', [
			'key' => 'primary_hand_current_step_count',
			'value' => 50
		]);

		$this->settings->track('primary_hand', 20);
		$this->assertDatabaseHas('settings', [
			'key' => 'primary_hand_current_step_count',
			'value' => 70
		]);

		$this->settings->track('primary_hand', -20);
		$this->assertDatabaseHas('settings', [
			'key' => 'primary_hand_current_step_count',
			'value' => 50
		]);
	}
}
