<?php

namespace Tests\Unit;

use \Mockery;
use Tests\TestCase;
use App\TheNovelist;
use PiPHP\GPIO\GPIO;
use App\AngleCalculator;
use App\PrimaryHandController;
use App\SecondaryHandController;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TheNovelistTest extends TestCase
{
    use RefreshDatabase;

    public $novelist;

    public function setUp()
	{
		parent::setUp();

		//arrange
		$this->seed('SettingsTableSeeder');
	    $mocked_filesystem = Mockery::mock('PiPHP\GPIO\FileSystem\FileSystemInterface')->makePartial();
	    $mocked_filesystem->shouldReceive('putContents');
	    $mocked_gpio = new GPIO($mocked_filesystem);

	    //assert
	    $primary_hand_controller = new PrimaryHandController($mocked_gpio);
	    $secondary_hand_controller = new SecondaryHandController($mocked_gpio);

	    $novelist = new TheNovelist($primary_hand_controller, $secondary_hand_controller);
	    $this->novelist = $novelist;
	    $this->novelist->settings->set('current_x', 10);
	    $this->novelist->settings->set('current_y', 10);
	}

	/**
	 * @test
	 * it can mock the gpio pin class
	 */
	public function it_can_mock_the_gpio_pin_class()
	{
	    $this->novelist->goto(10, 10.02);
	}

	/**
	 * @test
	 * it can move the primary hand in correct steps while moving upwards
	 */
	public function it_can_move_the_primary_hand_in_correct_steps_while_moving_upwards()
	{
		$angle_calculator = new AngleCalculator;
		$angle_calculator->setPrimaryHandLength(10);
		$angle_calculator->setSecondaryHandLength(10);
		$angle_calculator->setPoint(10, 15);

		$expeceted_steps_to_move_primary = abs($angle_calculator->getPrimaryHandAngle() / $this->novelist->settings->get('min_angle'));

		$this->novelist->goto(10, 15);
		$actual_primary_hand_moved_in_steps = $this->novelist->info()['primary_hand'][0];

		$this->assertEquals(round($expeceted_steps_to_move_primary), $actual_primary_hand_moved_in_steps);
	}

	/**
	 * @test
	 * it can move the secondary hand in correct steps while moving upwards
	 */
	public function it_can_move_the_secondary_hand_in_correct_steps_while_moving_upwards()
	{
		$angle_calculator = new AngleCalculator;
		$angle_calculator->setPrimaryHandLength(10);
		$angle_calculator->setSecondaryHandLength(10);
		$angle_calculator->setPoint(10, 15);

		$expeceted_steps_to_move_secondary = abs($angle_calculator->getSecondaryHandAngle() / $this->novelist->settings->get('min_angle'));

		$this->novelist->goto(10, 15);
		$actual_secondary_hand_moved_in_steps = $this->novelist->info()['secondary_hand'][0];

		$this->assertEquals(round($expeceted_steps_to_move_secondary), $actual_secondary_hand_moved_in_steps);
	}

}
