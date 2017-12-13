<?php

namespace App\Console\Commands;

use App\AngleCalculator;
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
        
        $calculator->setPrimaryHandLength(17.5);
        $calculator->setSecondaryHandLength(15.5);
        $calculator->setPoint($this->argument('x'), $this->argument('y'));

        echo $calculator->getPrimaryHandAngle();
    }
}
