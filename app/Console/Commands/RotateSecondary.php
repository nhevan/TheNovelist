<?php

namespace App\Console\Commands;

use App\Setting;
use Illuminate\Console\Command;
use App\SecondaryHandController;

class RotateSecondary extends Command
{
    protected $settings;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RotateSecondary {angle} {--cw}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotates the secondary hand to given angle.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->settings = new Setting;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $secondaryHandMover = new SecondaryHandController;
        $angle = $this->argument('angle');

        $steps_to_move =  abs($angle / .087891);
        $steps_to_move = floor($steps_to_move);
        $secondaryHandMover->setStepsToMove($steps_to_move);

        if($this->option('cw')){
            $secondaryHandMover->rotateClockwise();
            $this->settings->reset('secondary_hand');
            return 0;
        }

        $secondaryHandMover->rotateAntiClockwise();
        $this->settings->reset('secondary_hand');

        return 0;
    }
}
