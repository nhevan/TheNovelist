<?php

namespace App\Console\Commands;

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
        
        $calculator->setPrimaryHandLength(18.5);
        $calculator->setSecondaryHandLength(15.5);
        $calculator->setPoint($this->argument('x'), $this->argument('y'));

        $angle_to_rotate = $calculator->getPrimaryHandAngle();
        echo "Angle to rotate = {$angle_to_rotate}";
        $steps_to_move = floor($angle_to_rotate / .087891);
        echo "Steps to move = {$steps_to_move}";

        $primaryHandMover = new PrimaryHandController;
        $primaryHandMover->setStepsToMove($steps_to_move);
        $primaryHandMover->rotateClockwise();
    }
}
