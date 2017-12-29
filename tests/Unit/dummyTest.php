<?php

namespace Tests\Unit;

use \Mockery;
use Tests\TestCase;
use App\TheNovelist;
use PiPHP\GPIO\GPIO;
use App\HandController;
use App\PrimaryHandController;
use PiPHP\GPIO\FileSystem\FileSystem;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class dummyTest extends TestCase
{
    use RefreshDatabase;

	/**
	 * @test
	 * it can mock the gpio pin class
	 */
	public function it_can_mock_the_gpio_pin_class()
	{
		//arrange
		$this->seed('SettingsTableSeeder');
	    $mocked_filesystem = Mockery::mock('PiPHP\GPIO\FileSystem\FileSystemInterface')->makePartial();
	    $mocked_filesystem->shouldReceive('putContents')
		    ->andReturn(true);
	    $mocked_gpio = new GPIO($mocked_filesystem);

	    //assert
	    $primary_hand_controller = new PrimaryHandController($mocked_gpio);

	    $primary_hand_controller->rotate(10);
	}

}
