<?php

namespace App;

use App\Setting;
use App\PathTraverser;
use App\AngleCalculator;
use App\PrimaryHandController;
use Illuminate\Console\Command;
use App\SecondaryHandController;

class TheNovelist
{
    protected $secondaryHandMover;
    protected $primaryHandMover;
    public $settings;
    protected $calculator;
    protected $path_traverser;
    protected $loop_increment_value;
    private $target_x;
    private $target_y;
    private $verbose;
    private $primary_hand_total_steps_moved = 0;
    private $primary_hand_rotation_direction;
    private $secondary_hand_total_steps_moved = 0;
    private $secondary_hand_rotation_direction;

    public function __construct($secondaryHandMover = null, $primaryHandMover = null, $settings = null, $calculator = null, $path_traverser = null, $loop_increment_value = .02)
    {
        $this->primaryHandMover = $primaryHandMover ?: new PrimaryHandController;
        $this->secondaryHandMover = $secondaryHandMover ?: new SecondaryHandController;
        $this->settings = $settings ?: new Setting;
        
        $this->calculator = $calculator ?: new AngleCalculator;
        $this->calculator->setPrimaryHandLength(10);
        $this->calculator->setSecondaryHandLength(10);

        $this->path_traverser = $path_traverser ?: new PathTraverser;
        $this->loop_increment_value = $loop_increment_value;
    }

    /**
     * moves the pen head to given x y coordinate
     * @param  [type] $x_cord [description]
     * @param  [type] $y_cord [description]
     * @return [type]         [description]
     */
    public function goto($x_cord, $y_cord, $verbose = 0)
    {
        $this->target_x = $x_cord;
        $this->target_y = $y_cord;
        $this->verbose = $verbose;

        $this->path_traverser->setX1($this->settings->get('current_x'));
        $this->path_traverser->setY1($this->settings->get('current_y'));
        $this->path_traverser->setX2($x_cord);
        $this->path_traverser->setY2($y_cord);

        if($this->isVerticalLine()){
            if ($this->shouldDrawUpward()) {
                $response = $this->drawUpward();

                return $response;
            }

            if ($this->shouldDrawDownward()) {
                $response = $this->drawDownward();

                return response;
            }
        }

        if ($x_cord < $this->settings->get('current_x') ) { 
            for ($x = $this->settings->get('current_x'); $x >= $x_cord ; $x-=$this->loop_increment_value) { 
                $y = $this->path_traverser->getYWhenX($x);
                $this->calculator->setPoint($x, $y);
                $this->settings->set('current_x', round($x, 2));
                $this->settings->set('current_y', round($y, 2));
                $this->moveHands();
            }

            return 0;
        }
        if ($x_cord > $this->settings->get('current_x') ) {
            for ($x = $this->settings->get('current_x')+$this->loop_increment_value; $x <= $x_cord ; $x+=$this->loop_increment_value) { 
                $y = $this->path_traverser->getYWhenX($x);
                $this->calculator->setPoint($x, $y);
                $this->settings->set('current_x', round($x, 2));
                $this->settings->set('current_y', round($y, 2));
                $this->moveHands();
            }

            return 0;
        }
    }

    /**
     * determines if the pen head should move/draw downwards
     * @return [type] [description]
     */
    private function shouldDrawDownward()
    {
        return $this->target_y < $this->settings->get('current_y');
    }

    /**
     * draws a straight line downwards
     * @return [type] [description]
     */
    private function drawDownward()
    {
        if ($this->verbose) {
            $this->printPreRotationMessages("Drawing Downward");
        }
        for ($y = $this->settings->get('current_y')+$this->loop_increment_value; $y >= $this->target_y ; $y-=$this->loop_increment_value) { 
            $this->calculator->setPoint($this->settings->get('current_x'), $y);
            $this->settings->set('current_y', round($y, 2));
            $this->moveHands();
        }
        if ($this->verbose) {
            $this->printPostRotationMessages();
        }
    }

    /**
     * determines if the pen head should move/draw upwards
     * @return [type] [description]
     */
    private function shouldDrawUpward()
    {
        return $this->target_y > $this->settings->get('current_y');
    }

    /**
     * draws a straight line upwards
     * @return [type] [description]
     */
    private function drawUpward()
    {
        if ($this->verbose) {
            $this->printPreRotationMessages("Drawing Upward");
        }
        for ($y = $this->settings->get('current_y')+$this->loop_increment_value; $y <= $this->target_y ; $y+=$this->loop_increment_value) { 
            $this->calculator->setPoint($this->settings->get('current_x'), $y);
            $this->settings->set('current_y', round($y, 2));
            $this->moveHands();
        }
        if ($this->verbose) {
            $this->printPostRotationMessages();
        }
    }

    /**
     * prints verbose messages prior to moving hands in desired direction
     * @return [type] [description]
     */
    private function printPreRotationMessages($direction = 'unknown')
    {
        $angle_calculator = new AngleCalculator;
        $angle_calculator->setPrimaryHandLength($this->calculator->getPrimaryHandLength());
        $angle_calculator->setSecondaryHandLength($this->calculator->getSecondaryHandLength());

        $current_x = $this->settings->get('current_x');
        $current_y = $this->settings->get('current_y');
        $angle_calculator->setPoint($this->target_x, $this->target_y);

        echo "\r\n================{$direction}===============";
        echo "\r\nDistance of pixel from origin : {$angle_calculator->getDistance()}";
        echo "\r\nCurrent X : {$current_x}";
        echo "\r\nCurrent Y : {$current_y}";
        echo "\r\nTarget X : {$this->target_x}";
        echo "\r\nTarget Y : {$this->target_y}";
        echo "\r\n=============================================\r\n";
    }

    private function printPostRotationMessages()
    {
        echo "\r\n===============Drawing Complete==============";
        echo "\r\nPrimary hand moved a total of : {$this->primary_hand_total_steps_moved} steps";
        echo "\r\nPrimary hand rotation direction : {$this->primary_hand_rotation_direction}";
        echo "\r\nSecondary hand moved a total of : {$this->secondary_hand_total_steps_moved} steps";
        echo "\r\nSecondary hand rotation direction : {$this->secondary_hand_rotation_direction}";
        echo "\r\n=============================================\r\n";
    }

    /**
     * checks if the line to draw is a vertical line
     * @return boolean [description]
     */
    private function isVerticalLine()
    {
        return $this->target_x == $this->settings->get('current_x');
    }

    /**
     * moves the hands in desired direction
     * @return [type] [description]
     */
    private function moveHands()
    {
        $primary_vector = $this->primaryHandMover->rotate($this->calculator->getPrimaryHandAngle(), $this->verbose - 1);
        $secondary_vector = $this->secondaryHandMover->rotate($this->calculator->getSecondaryHandAngle(), $this->verbose - 1);

        $this->primary_hand_total_steps_moved += $primary_vector[0];
        $this->primary_hand_rotation_direction = $primary_vector[1];

        $this->secondary_hand_total_steps_moved += $secondary_vector[0];
        $this->secondary_hand_rotation_direction = $secondary_vector[1];
    }
}
