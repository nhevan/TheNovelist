<?php

namespace App\Console\Commands;

use App\Setting;
use App\AngleCalculator;
use App\PrimaryHandController;
use Illuminate\Console\Command;
use App\SecondaryHandController;

class Move extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Move {x} {y}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Moves the pen head to given coordinates.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $secondaryHandMover = new SecondaryHandController;
        $primaryHandMover = new PrimaryHandController;
        $calculator = new AngleCalculator();
        $settings = new Setting();
        
        $calculator->setPrimaryHandLength(14.75);
        $calculator->setSecondaryHandLength(15.5);
        $calculator->setPoint($this->argument('x'), $this->argument('y'));

        $target_primary_angle = $calculator->getPrimaryHandAngle();
        $current_primary_hand_angle = $settings->getCurrentHandAngle('primary_hand');
        $angle_to_rotate_primary_hand = $current_primary_hand_angle - $target_primary_angle;
        $steps_to_move_primary_hand = $angle_to_rotate_primary_hand / .087891;
        $steps_to_move_primary_hand = floor($steps_to_move_primary_hand);

        $target_secondary_angle = $calculator->getSecondaryHandAngle();
        $current_secondary_hand_angle = $settings->getCurrentHandAngle('secondary_hand');
        $angle_to_rotate_secondary_hand = $current_secondary_hand_angle - $target_secondary_angle;
        $steps_to_move_secondary_hand = $angle_to_rotate_secondary_hand / .087891;
        $steps_to_move_secondary_hand = floor($steps_to_move_secondary_hand);

        echo "Current angle = {$current_secondary_hand_angle}";
        echo "Angle to rotate = {$angle_to_rotate_secondary_hand}";
        echo "Steps to move = {$steps_to_move_secondary_hand}";

        
        $primaryHandMover->setStepsToMove(abs($steps_to_move_primary_hand));
        if ($angle_to_rotate_primary_hand > 0) {
            $primaryHandMover->rotateClockwise();
        }else{
            $primaryHandMover->rotateAntiClockwise();
        }

        $secondaryHandMover->setStepsToMove(abs($steps_to_move_secondary_hand));
        if ($angle_to_rotate_secondary_hand > 0) {
            $secondaryHandMover->rotateClockwise();
        }else{
            $secondaryHandMover->rotateAntiClockwise();
        }

    }
}
