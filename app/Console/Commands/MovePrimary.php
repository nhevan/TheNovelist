<?php

namespace App\Console\Commands;

use App\Setting;
use App\AngleCalculator;
use App\PrimaryHandController;
use Illuminate\Console\Command;

class MovePrimary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MovePrimary {x} {y}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Returns the primary hand angle with the x axis.';

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
        $calculator = new AngleCalculator();
        $settings = new Setting();
        
        $calculator->setPrimaryHandLength(14.75);
        $calculator->setSecondaryHandLength(15.5);
        $calculator->setPoint($this->argument('x'), $this->argument('y'));

        $target_angle = $calculator->getPrimaryHandAngle();
        $current_hand_angle = $settings->getCurrentHandAngle('primary_hand');
        $angle_to_rotate = $current_hand_angle - $target_angle;

        $steps_to_move = $angle_to_rotate / .087891;
        $steps_to_move = floor($steps_to_move);

        echo "Current angle = {$current_hand_angle}";
        echo "Angle to rotate = {$angle_to_rotate}";
        echo "Steps to move = {$steps_to_move}";

        $primaryHandMover = new PrimaryHandController;
        $primaryHandMover->setStepsToMove(abs($steps_to_move));
        if ($angle_to_rotate > 0) {
            return $primaryHandMover->rotateClockwise();
        }

        return $primaryHandMover->rotateAntiClockwise();
    }
}
