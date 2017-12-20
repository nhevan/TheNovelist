<?php

namespace App\Console\Commands;

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
        
        $calculator->setPrimaryHandLength(15);
        $calculator->setSecondaryHandLength(10);
        $calculator->setPoint($this->argument('x'), $this->argument('y'));

        $primaryHandMover->rotate($calculator->getPrimaryHandAngle());
        $secondaryHandMover->rotate($calculator->getSecondaryHandAngle());
    }
}
