<?php

namespace App;

use App\HandController;

class PrimaryHandController extends HandController
{
	/**
	 * sets up all the necessary pins required to control the motor
	 * @return [type] [description]
	 */
	protected function setupPins()
	{
		$this->motor_switch = $this->gpio->getOutputPin(18);
		$this->input_1 = $this->gpio->getOutputPin(17); // this is input 2 in motor driver
		$this->input_2 = $this->gpio->getOutputPin(24); // this is input 4 in motor driver
		$this->input_3 = $this->gpio->getOutputPin(4); // this is input 1 in motor driver
		$this->input_4 = $this->gpio->getOutputPin(23); // this is input 3 in motor driver
	}

	/**
	 * rotates the motor clock wise
	 * @return [type] [description]
	 */
	public function rotateClockwise()
	{
		parent::rotateClockwise();
		$this->settings->track('primary_hand', -1 * $this->getStepsToMove()); // clockwise step is considered as a negative step
	}

	/**
	 * rotates the motor anti clock wise
	 * @return [type] [description]
	 */
	public function rotateAntiClockwise()
	{
		parent::rotateAntiClockwise();
		$this->settings->track('primary_hand', $this->getStepsToMove()); // anti clockwise step is considered as a positive step
	}
}
