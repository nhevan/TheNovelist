<?php

namespace App\Console\Commands;

use App\Setting;
use App\PrimaryHandController;
use Illuminate\Console\Command;

class RotatePrimary extends Command
{
    protected $settings;
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

        $this->settings = new Setting;
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

        $steps_to_move =  abs($angle / .087891);
        $steps_to_move = floor($steps_to_move);
        $primaryHandMover->setStepsToMove($steps_to_move);

        if($this->option('cw')){
            $primaryHandMover->rotateClockwise();
            $this->settings->reset('primary_hand');
            return 0;
        }

        $primaryHandMover->rotateAntiClockwise();
        $this->settings->reset('primary_hand');

        return 0;
    }
}
