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

	/**
	 * @test
	 * it can reset the step count for any hand
	 */
	public function it_can_reset_the_step_count_for_any_hand()
	{
		$this->settings->track('primary_hand', 50);
		$this->settings->reset('primary_hand');
		$this->assertDatabaseHas('settings', [
			'key' => 'primary_hand_current_step_count',
			'value' => 0
		]);
	}

	/**
	 * @test
	 * it can return the current angle between the primary hand and x axis
	 */
	public function it_can_return_the_current_angle_between_the_primary_hand_and_x_axis()
	{
		//arrange
	    $this->settings->track('primary_hand', 512);
	
	    //act
		$current_primary_hand_angle = $this->settings->getCurrentHandAngle('primary_hand');
	
	    //assert
	    $this->assertEquals(45, round($current_primary_hand_angle));
	}
}
