<?php

namespace Tests\Feature;

use App\Setting;
use Tests\TestCase;
use App\PathTraverser;
use App\AngleCalculator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RotationDirectionTest extends TestCase
{
	use RefreshDatabase;

	protected $calculator;
	protected $path_traverser;
	protected $settings;

	public function setUp()
	{
		parent::setUp();

		$this->calculator = new AngleCalculator;
		$this->path_traverser = new PathTraverser;
		$this->settings = new Setting;
		$this->seed('SettingsTableSeeder');
	}

	/**
	 * @test
	 * primary hand rotates ccw when coord 15 15 is given and current coord is 15 10
	 */
	public function primary_hand_rotates_ccw_when_coord_15_15_is_given_and_current_coord_is_15_10()
	{
		//arrange
		$this->settings->set('current_x', 15);
		$this->settings->set('current_y', 10);
		$loop_increment_value = .02;
        
		$this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(10);

        $this->path_traverser->setX1($this->settings->get('current_x'));
        $this->path_traverser->setY1($this->settings->get('current_y'));
        $this->path_traverser->setX2(15);
        $this->path_traverser->setY2(15);

        $y = $this->settings->get('current_y') + $loop_increment_value;
        $this->calculator->setPoint($this->settings->get('current_x'), $y);
        $this->settings->set('current_y', round($y, 2));
	    
	    //act
		$direction = $this->getRotationDirection('primary_hand');

	    //assert
	    $this->assertEquals('CCW', $direction);
	}

	/**
	 * @test
	 * secondary hand rotates cw when coord 15 15 is given and current coord is 15 10
	 */
	public function secondary_hand_rotates_ccw_when_coord_15_15_is_given_and_current_coord_is_15_10()
	{
		//arrange
		$this->settings->set('current_x', 15);
		$this->settings->set('current_y', 10);
		$loop_increment_value = .02;
        
		$this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(10);

        $this->path_traverser->setX1($this->settings->get('current_x'));
        $this->path_traverser->setY1($this->settings->get('current_y'));
        $this->path_traverser->setX2(15);
        $this->path_traverser->setY2(15);

        $y = $this->settings->get('current_y') + $loop_increment_value;
        $this->calculator->setPoint($this->settings->get('current_x'), $y);
        $this->settings->set('current_y', round($y, 2));
	    
	    //act
		$direction = $this->getRotationDirection('secondary_hand');

	    //assert
	    $this->assertEquals('CW', $direction);
	}

	/**
	 * @test
	 * primary hand rotates ccw when coord 10 15 is given and current coord is 15 15
	 */
	public function primary_hand_rotates_ccw_when_coord_10_15_is_given_and_current_coord_is_15_15()
	{
		//arrange
		$this->settings->set('current_x', 15);
		$this->settings->set('current_y', 15);
		$loop_increment_value = .02;
        
		$this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(10);

        $this->path_traverser->setX1($this->settings->get('current_x'));
        $this->path_traverser->setY1($this->settings->get('current_y'));
        $this->path_traverser->setX2(10);
        $this->path_traverser->setY2(15);

        $y = $this->settings->get('current_y') + $loop_increment_value;
        $this->calculator->setPoint($this->settings->get('current_x'), $y);
        $this->settings->set('current_y', round($y, 2));
	    
	    //act
		$direction = $this->getRotationDirection('primary_hand');

	    //assert
	    $this->assertEquals('CCW', $direction);
	}

	/**
	 * @test
	 * secondary hand rotates cw when coord 10 15 is given and current coord is 15 15
	 */
	public function secondary_hand_rotates_ccw_when_coord_15_15_is_given_and_current_coord_is_15_15()
	{
		//arrange
		$this->settings->set('current_x', 15);
		$this->settings->set('current_y', 15);
		$loop_increment_value = .02;
        
		$this->calculator->setPrimaryHandLength(15);
        $this->calculator->setSecondaryHandLength(10);

        $this->path_traverser->setX1($this->settings->get('current_x'));
        $this->path_traverser->setY1($this->settings->get('current_y'));
        $this->path_traverser->setX2(10);
        $this->path_traverser->setY2(15);

        $y = $this->settings->get('current_y') + $loop_increment_value;
        $this->calculator->setPoint($this->settings->get('current_x'), $y);
        $this->settings->set('current_y', round($y, 2));
	    
	    //act
		$direction = $this->getRotationDirection('secondary_hand');

	    //assert
	    $this->assertEquals('CCW', $direction);
	}

	protected function getRotationDirection($hand_name)
    {
        if ($hand_name == 'primary_hand') {
            $target_angle = $this->calculator->getPrimaryHandAngle();
        }else{
            $target_angle = $this->calculator->getSecondaryHandAngle();
        }
        
        $current_hand_angle = $this->settings->getCurrentHandAngle($hand_name);

        if($hand_name == 'primary_hand'){
	        $angle_to_rotate_hand = $current_hand_angle - $target_angle; // this is where the jhamela starts
	    }else{
        	// dd($target_angle);
	    	$angle_to_rotate_hand = $current_hand_angle - $target_angle;
	    }

        if($hand_name == 'primary_hand'){
            if ($angle_to_rotate_hand > 0) {
                return "CW";
            }else{
                return "CCW";
            }
        }else{
            if ($angle_to_rotate_hand > 0) {
                return "CCW";
            }else{
                return "CW";
            }
        }
    }
}
