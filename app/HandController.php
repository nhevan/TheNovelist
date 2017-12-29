<?php

namespace App;

use App\Setting;
use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\PinInterface;

abstract class HandController
{
	public $zoom_value;
	protected $gpio;
	protected $settings;
	protected $motor_switch;
    protected $input_1; // orange
	protected $input_2; // yellow
	protected $input_3; // pink
	protected $input_4; // blue

	protected $current_phase = 0;
	protected $step_count = 1;
	protected $steps_to_move;
	protected $delay = 5000; //in micro second
	protected $phase_sequence;

	public function __construct($gpio = null)
	{
		$this->gpio = $gpio ? $gpio : new GPIO;
		$this->settings = new Setting;
		$this->phase_sequence = $this->setPhaseSequences();
		$this->setupPins();
		$this->step_count = $this->settings->get('step_count');
		$this->zoom_value = 1;
	}

	/**
	 * sets up all the necessary pins required to control the motor
	 * @return [type] [description]
	 */
	abstract protected function setupPins();

    /**
     * @return mixed
     */
    public function getStepsToMove()
    {
        return $this->steps_to_move;
    }

    /**
     * @param mixed $steps_to_move
     *
     * @return self
     */
    public function setStepsToMove($steps_to_move)
    {
        $this->steps_to_move = $steps_to_move;

        return $this;
    }

    /**
     * turns On the motor
     * @return [type] [description]
     */
    protected function turnOnMotor()
    {
    	// echo "Turning On Motor";
		$this->motor_switch->setValue(PinInterface::VALUE_HIGH);
    }
    
    /**
     * turns off the motor
     * @return [type] [description]
     */
    protected function turnOffMotor()
    {
    	// echo "Turning Off Motor";
    	$this->motor_switch->setValue(PinInterface::VALUE_LOW);
    }

    /**
	 * moves the motor by 1 step as per given values
	 * @param  [type] $w1 [description]
	 * @param  [type] $w2 [description]
	 * @param  [type] $w3 [description]
	 * @param  [type] $w4 [description]
	 * @return [type]     [description]
	 */
	protected function moveStep($w1, $w2, $w3, $w4)
	{
		$this->input_1->setValue($w1);
		$this->input_2->setValue($w2);
		$this->input_3->setValue($w3);
		$this->input_4->setValue($w4);
	}

	/**
	 * prepares the set sequence for the stepper motor 28BYJ48
	 */
	protected function setPhaseSequences()
	{
		$phase_sequence = [];
		$phase_sequence[0] = [PinInterface::VALUE_LOW, PinInterface::VALUE_HIGH,PinInterface::VALUE_LOW, PinInterface::VALUE_LOW] ;
		$phase_sequence[1] = [PinInterface::VALUE_LOW, PinInterface::VALUE_HIGH,PinInterface::VALUE_LOW, PinInterface::VALUE_HIGH];
		$phase_sequence[2] = [PinInterface::VALUE_LOW, PinInterface::VALUE_LOW, PinInterface::VALUE_LOW, PinInterface::VALUE_HIGH];
		$phase_sequence[3] = [PinInterface::VALUE_HIGH,PinInterface::VALUE_LOW, PinInterface::VALUE_LOW, PinInterface::VALUE_HIGH];
		$phase_sequence[4] = [PinInterface::VALUE_HIGH,PinInterface::VALUE_LOW, PinInterface::VALUE_LOW, PinInterface::VALUE_LOW] ;
		$phase_sequence[5] = [PinInterface::VALUE_HIGH,PinInterface::VALUE_LOW, PinInterface::VALUE_HIGH,PinInterface::VALUE_LOW] ;
		$phase_sequence[6] = [PinInterface::VALUE_LOW, PinInterface::VALUE_LOW, PinInterface::VALUE_HIGH,PinInterface::VALUE_LOW] ;
		$phase_sequence[7] = [PinInterface::VALUE_LOW, PinInterface::VALUE_HIGH,PinInterface::VALUE_HIGH,PinInterface::VALUE_LOW] ;

		return $phase_sequence;
	}

	/**
	 * rotates the motor clock wise
	 * @return [type] [description]
	 */
	public function rotateClockwise()
	{
		$this->turnOnMotor();
		for ($step=0; $step < $this->getStepsToMove(); $step++) { 
			for ($local_step_count=0; $local_step_count <= $this->step_count ; $local_step_count++) { 
				$this->moveStep($this->phase_sequence[$this->current_phase][0], $this->phase_sequence[$this->current_phase][1], $this->phase_sequence[$this->current_phase][2], $this->phase_sequence[$this->current_phase][3]);
				
				$this->current_phase -= 1;
				if($this->current_phase < 0)
					$this->current_phase = 7;
				usleep($this->delay);
			}
		}
		$this->turnOffMotor();
	}

	/**
	 * rotates the motor anti clock wise
	 * @return [type] [description]
	 */
	public function rotateAntiClockwise()
	{
		$this->turnOnMotor();
		for ($step=0; $step < $this->getStepsToMove(); $step++) { 
			for ($local_step_count=0; $local_step_count <= $this->step_count ; $local_step_count++) { 
				$this->moveStep($this->phase_sequence[$this->current_phase][0], $this->phase_sequence[$this->current_phase][1], $this->phase_sequence[$this->current_phase][2], $this->phase_sequence[$this->current_phase][3]);
				$this->current_phase += 1;
				if($this->current_phase > 7)
					$this->current_phase = 0;
				usleep($this->delay);
			}
		}
		$this->turnOffMotor();
	}
}
