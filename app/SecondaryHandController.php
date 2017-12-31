<?php

namespace App;

class SecondaryHandController extends HandController
{
    /**
	 * sets up all the necessary pins required to control the motor
	 * @return [type] [description]
	 */
	protected function setupPins()
	{
		$this->motor_switch = $this->gpio->getOutputPin(26);
		$this->input_1 = $this->gpio->getOutputPin(5); 	// this is input 2 in motor driver
		$this->input_2 = $this->gpio->getOutputPin(6); 	// this is input 4 in motor driver
		$this->input_3 = $this->gpio->getOutputPin(13);	// this is input 1 in motor driver
		$this->input_4 = $this->gpio->getOutputPin(19);	// this is input 3 in motor driver
	}

	/**
	 * rotates the hand in desired direction
	 * @return [type] [description]
	 */
	public function rotate($target_secondary_angle, $verbose = 0)
	{
        if($verbose > 0) echo "\r\n===============Move Secondary================\r\n";
		$current_secondary_hand_angle = $this->settings->getCurrentHandAngle('secondary_hand');
        $angle_to_rotate_secondary_hand = $current_secondary_hand_angle - $target_secondary_angle;
        $angle_to_rotate_secondary_hand = round($angle_to_rotate_secondary_hand, 6);
        $steps_to_move_secondary_hand = $angle_to_rotate_secondary_hand / ($this->settings->get('step_count') * $this->settings->get('min_angle'));

        $steps_to_move_secondary_hand = round(abs($steps_to_move_secondary_hand));
        $steps_to_move_secondary_hand = $steps_to_move_secondary_hand * $this->zoom_value;

        if($verbose > 0) echo "Current Secondary Hand angle = {$current_secondary_hand_angle} \r\n";
        if($verbose > 0) echo "Angle to rotate Secondary Hand = {$angle_to_rotate_secondary_hand} \r\n";
        if($verbose > 0) echo "Steps to move Secondary Hand = {$steps_to_move_secondary_hand} \r\n";

        $this->setStepsToMove(abs($steps_to_move_secondary_hand));
        if ($angle_to_rotate_secondary_hand > 0) {
            $this->rotateAntiClockwise();
            if($verbose > 0) echo "Rotation direction : Anti Clockwise \r\n";

            return [$steps_to_move_secondary_hand, 'Anti Clockwise'];
        }else{
            $this->rotateClockwise();
            if($verbose > 0) echo "Rotation direction : Clockwise \r\n";

            return [$steps_to_move_secondary_hand, 'Clockwise'];
        }
        if($verbose > 0) echo "=============================================\r\n";
	}

	/**
	 * rotates the motor clock wise
	 * @return [type] [description]
	 */
	public function rotateClockwise()
	{
		parent::rotateClockwise();
		$this->settings->track('secondary_hand', $this->getStepsToMove()); // clockwise step is considered as a negative step
	}

	/**
	 * rotates the motor anti clock wise
	 * @return [type] [description]
	 */
	public function rotateAntiClockwise()
	{
		parent::rotateAntiClockwise();
		$this->settings->track('secondary_hand', -1 * $this->getStepsToMove()); // anti clockwise step is considered as a positive step
	}
}
