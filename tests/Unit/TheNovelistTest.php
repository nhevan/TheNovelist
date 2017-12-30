<?php

namespace Tests\Unit;

use \Mockery;
use Tests\TestCase;
use App\TheNovelist;
use PiPHP\GPIO\GPIO;
use App\PrimaryHandController;
use App\SecondaryHandController;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TheNovelistTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
	{
		parent::setUp();
	}

	/**
	 * @test
	 * it can mock the gpio pin class
	 */
	public function it_can_mock_the_gpio_pin_class()
	{
		//arrange
		$this->seed('SettingsTableSeeder');
	    $mocked_filesystem = Mockery::mock('PiPHP\GPIO\FileSystem\FileSystemInterface')->makePartial();
	    $mocked_filesystem->shouldReceive('putContents');
	    $mocked_gpio = new GPIO($mocked_filesystem);

	    //assert
	    $primary_hand_controller = new PrimaryHandController($mocked_gpio);
	    $secondary_hand_controller = new SecondaryHandController($mocked_gpio);

	    $novelist = new TheNovelist($primary_hand_controller, $secondary_hand_controller);
	    $novelist->settings->set('current_x', 10);
	    $novelist->settings->set('current_y', 10);

	    $novelist->goto(10, 10.02, 1);
	}

	/**
	 * @test
	 * it can move pen 1 step upward
	 */
	public function it_can_move_pen_1_step_upward()
	{
		//arrange
	    
	
	    //act
		
	
	    //assert
	    
	}

}
