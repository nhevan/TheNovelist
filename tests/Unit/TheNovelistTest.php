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
    public $angle_calculator;

    public function setUp()
	{
		parent::setUp();
		$primary_hand_length = 15;
		$secondary_hand_length = 11;
		$loop_increment_value = .005;

		//arrange
		$this->seed('SettingsTableSeeder');
	    $mocked_filesystem = Mockery::mock('PiPHP\GPIO\FileSystem\FileSystemInterface')->makePartial();
	    $mocked_filesystem->shouldReceive('putContents');
	    $mocked_gpio = new GPIO($mocked_filesystem);

	    //assert
	    $primary_hand_controller = new PrimaryHandController($mocked_gpio);
	    $secondary_hand_controller = new SecondaryHandController($mocked_gpio);

	    $novelist = new TheNovelist($primary_hand_controller, $secondary_hand_controller, null, null, null, $loop_increment_value);
	    $this->novelist = $novelist;
	    $this->novelist->settings->set('current_x', $primary_hand_length);
	    $this->novelist->settings->set('current_y', $secondary_hand_length);
	    
	    $angle_calculator = new AngleCalculator;
		$angle_calculator->setPrimaryHandLength($primary_hand_length);
		$angle_calculator->setSecondaryHandLength($secondary_hand_length);
		$this->angle_calculator = $angle_calculator;
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
	 * it can move pen head 1 px upward
	 */
	public function it_can_move_pen_head_1_px_upward()
	{
		//arrange
		$this->angle_calculator->setPoint(15, 11.02);
	
	    //act
		$expeceted_steps_to_move_primary = abs($this->angle_calculator->getPrimaryHandAngle() / $this->novelist->settings->get('min_angle'));
		$expeceted_steps_to_move_secondary = abs($this->angle_calculator->getSecondaryHandAngle() / $this->novelist->settings->get('min_angle'));

		$this->novelist->goto(15, 11.02, 1);
		$actual_primary_hand_moved_in_steps = $this->novelist->info()['primary_hand'][0];
		$actual_secondary_hand_moved_in_steps = $this->novelist->info()['secondary_hand'][0];

	    //assert
	    $this->assertEquals(round($expeceted_steps_to_move_primary), $actual_primary_hand_moved_in_steps);
		$this->assertEquals(round($expeceted_steps_to_move_secondary), $actual_secondary_hand_moved_in_steps);
	}

	/**
	 * @test
	 * it can move pen head 1 px downward
	 */
	public function it_can_move_pen_head_1_px_downward()
	{
		//arrange
		$this->angle_calculator->setPoint(15, 10.98);
	
	    //act
		$expeceted_steps_to_move_primary = abs($this->angle_calculator->getPrimaryHandAngle() / $this->novelist->settings->get('min_angle'));
		$expeceted_steps_to_move_secondary = abs($this->angle_calculator->getSecondaryHandAngle() / $this->novelist->settings->get('min_angle'));

		$this->novelist->goto(15, 10.98, 1);
		$actual_primary_hand_moved_in_steps = $this->novelist->info()['primary_hand'][0];
		$actual_secondary_hand_moved_in_steps = $this->novelist->info()['secondary_hand'][0];

	    //assert
	    $this->assertEquals(round($expeceted_steps_to_move_primary), $actual_primary_hand_moved_in_steps);
		$this->assertEquals(round($expeceted_steps_to_move_secondary), $actual_secondary_hand_moved_in_steps);
	}

	/**
	 * @test
	 * it can move pen head 1 px right
	 */
	public function it_can_move_pen_head_1_px_right()
	{
		//arrange
		$this->angle_calculator->setPoint(15.02, 11);
	
	    //act
		$expeceted_steps_to_move_primary = abs($this->angle_calculator->getPrimaryHandAngle() / $this->novelist->settings->get('min_angle'));
		$expeceted_steps_to_move_secondary = abs($this->angle_calculator->getSecondaryHandAngle() / $this->novelist->settings->get('min_angle'));

		$this->novelist->goto(15.02, 11, 1);
		$actual_primary_hand_moved_in_steps = $this->novelist->info()['primary_hand'][0];
		$actual_secondary_hand_moved_in_steps = $this->novelist->info()['secondary_hand'][0];

	    //assert
	    $this->assertEquals(round($expeceted_steps_to_move_primary), $actual_primary_hand_moved_in_steps);
		$this->assertEquals(round($expeceted_steps_to_move_secondary), $actual_secondary_hand_moved_in_steps);
	}

	/**
	 * @test
	 * it can move pen head 1 px left
	 */
	public function it_can_move_pen_head_1_px_left()
	{
		//arrange
		$this->angle_calculator->setPoint(14.98, 11);
	
	    //act
		$expeceted_steps_to_move_primary = abs($this->angle_calculator->getPrimaryHandAngle() / $this->novelist->settings->get('min_angle'));
		$expeceted_steps_to_move_secondary = abs($this->angle_calculator->getSecondaryHandAngle() / $this->novelist->settings->get('min_angle'));

		$this->novelist->goto(14.98, 11, 1);
		$actual_primary_hand_moved_in_steps = $this->novelist->info()['primary_hand'][0];
		$actual_secondary_hand_moved_in_steps = $this->novelist->info()['secondary_hand'][0];

	    //assert
	    $this->assertEquals(round($expeceted_steps_to_move_primary), $actual_primary_hand_moved_in_steps);
		$this->assertEquals(round($expeceted_steps_to_move_secondary), $actual_secondary_hand_moved_in_steps);
	}

	/**
	 * @test
	 * it can move pen head 1 px top left
	 */
	public function it_can_move_pen_head_1_px_top_left()
	{
		//arrange
		$this->angle_calculator->setPoint(14.98, 11.02);
	
	    //act
		$expeceted_steps_to_move_primary = abs($this->angle_calculator->getPrimaryHandAngle() / $this->novelist->settings->get('min_angle'));
		$expeceted_steps_to_move_secondary = abs($this->angle_calculator->getSecondaryHandAngle() / $this->novelist->settings->get('min_angle'));

		$this->novelist->goto(14.98, 11.02, 1);
		$actual_primary_hand_moved_in_steps = $this->novelist->info()['primary_hand'][0];
		$actual_secondary_hand_moved_in_steps = $this->novelist->info()['secondary_hand'][0];

	    //assert
	    $this->assertEquals(round($expeceted_steps_to_move_primary), $actual_primary_hand_moved_in_steps);
		$this->assertEquals(round($expeceted_steps_to_move_secondary), $actual_secondary_hand_moved_in_steps);
	}

	/**
	 * @test
	 * it can move pen head 1 px top right
	 */
	public function it_can_move_pen_head_1_px_top_right()
	{
		//arrange
		$this->angle_calculator->setPoint(15.02, 11.02);
	
	    //act
		$expeceted_steps_to_move_primary = abs($this->angle_calculator->getPrimaryHandAngle() / $this->novelist->settings->get('min_angle'));
		$expeceted_steps_to_move_secondary = abs($this->angle_calculator->getSecondaryHandAngle() / $this->novelist->settings->get('min_angle'));

		$this->novelist->goto(15.02, 11.02, 1);
		$actual_primary_hand_moved_in_steps = $this->novelist->info()['primary_hand'][0];
		$actual_secondary_hand_moved_in_steps = $this->novelist->info()['secondary_hand'][0];

	    //assert
	    $this->assertEquals(round($expeceted_steps_to_move_primary), $actual_primary_hand_moved_in_steps);
		$this->assertEquals(round($expeceted_steps_to_move_secondary), $actual_secondary_hand_moved_in_steps);
	}

	/**
	 * @test
	 * it can move pen head 1 px bottom right
	 */
	public function it_can_move_pen_head_1_px_bottom_right()
	{
		//arrange
		$this->angle_calculator->setPoint(15.02, 10.98);
	
	    //act
		$expeceted_steps_to_move_primary = abs($this->angle_calculator->getPrimaryHandAngle() / $this->novelist->settings->get('min_angle'));
		$expeceted_steps_to_move_secondary = abs($this->angle_calculator->getSecondaryHandAngle() / $this->novelist->settings->get('min_angle'));

		$this->novelist->goto(15.02, 10.98, 1);
		$actual_primary_hand_moved_in_steps = $this->novelist->info()['primary_hand'][0];
		$actual_secondary_hand_moved_in_steps = $this->novelist->info()['secondary_hand'][0];

	    //assert
	    $this->assertEquals(round($expeceted_steps_to_move_primary), $actual_primary_hand_moved_in_steps);
		$this->assertEquals(round($expeceted_steps_to_move_secondary), $actual_secondary_hand_moved_in_steps);
	}

	/**
	 * @test
	 * it can move pen head 1 px bottom left
	 */
	public function it_can_move_pen_head_1_px_bottom_left()
	{
		//arrange
		$this->angle_calculator->setPoint(14.98, 10.98);
	
	    //act
		$expeceted_steps_to_move_primary = abs($this->angle_calculator->getPrimaryHandAngle() / $this->novelist->settings->get('min_angle'));
		$expeceted_steps_to_move_secondary = abs($this->angle_calculator->getSecondaryHandAngle() / $this->novelist->settings->get('min_angle'));
		$this->novelist->goto(14.98, 10.98, 1);
		// dd($this->novelist->calculator->getSecondaryHandAngle());
		$actual_primary_hand_moved_in_steps = $this->novelist->info()['primary_hand'][0];
		$actual_secondary_hand_moved_in_steps = $this->novelist->info()['secondary_hand'][0];

	    //assert
	    // $this->assertEquals(round($expeceted_steps_to_move_primary), $actual_primary_hand_moved_in_steps);
		$this->assertEquals(round($expeceted_steps_to_move_secondary), $actual_secondary_hand_moved_in_steps);
	}
}
