<?php

namespace App\Console\Commands;

use App\PrimaryHandController;
use Illuminate\Console\Command;

class RotatePrimary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RotatePrimary {angle} {--cw}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotates the primary hand to given angle.';

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
        $primaryHandMover = new PrimaryHandController;
        $angle = $this->argument('angle');

        $steps_to_move =  abs(floor($angle / .087891));
        $primaryHandMover->setStepsToMove($steps_to_move);

        if($this->option('cw')){
            return $primaryHandMover->rotateClockwise();
        }
        return $primaryHandMover->rotateAntiClockwise();
    }
}