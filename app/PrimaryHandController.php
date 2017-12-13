<?php

namespace App;

use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\PinInterface;

class PrimaryHandController
{
	protected $gpio;
    protected $input_1; // orange
	protected $input_2; // yellow
	protected $input_3; // pink
	protected $input_4; // blue

	protected $current_phase = 0;
	protected $step_count = 1;
	protected $steps_to_move;
	protected $delay = 1000; //in micro second
	protected $phase_sequence;

	public function __construct()
	{
		$this->gpio = new GPIO();
		$this->phase_sequence = $this->setPhaseSequences();
		$this->setupPins();
	}

	public function setPhaseSequences()
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

	public function setupPins()
	{
		$this->input_1 = $this->gpio->getOutputPin(17);
		$this->input_2 = $this->gpio->getOutputPin(24);
		$this->input_3 = $this->gpio->getOutputPin(4);
		$this->input_4 = $this->gpio->getOutputPin(23);
	}

	public function setStep($w1, $w2, $w3, $w4)
	{
		$this->input_1->setValue($w1);
		$this->input_2->setValue($w2);
		$this->input_3->setValue($w3);
		$this->input_4->setValue($w4);
	}

	public function rotateClockwise()
	{
		for ($step=0; $step < $this->getStepsToMove(); $step++) { 
			$this->setStep($this->phase_sequence[$this->current_phase][0], $this->phase_sequence[$this->current_phase][1], $this->phase_sequence[$this->current_phase][2], $this->phase_sequence[$this->current_phase][3]);
			$this->current_phase += 1;
			if($this->current_phase > 7)
				$this->current_phase = 0;
			usleep($this->delay);
		}
	}

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
}
