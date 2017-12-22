<?php

namespace App\Console\Commands;

use App\Setting;
use App\PathTraverser;
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
        $path_traverser = new PathTraverser();
        $settings = new Setting;
        $loop_increment_value = .02;
        
        $calculator->setPrimaryHandLength(15);
        $calculator->setSecondaryHandLength(10);

        $path_traverser->setX1($settings->get('current_x'));
        $path_traverser->setY1($settings->get('current_y'));
        $path_traverser->setX2($this->argument('x'));
        $path_traverser->setY2($this->argument('y'));

        if($this->argument('x') == $settings->get('current_x')){
            if ($this->argument('y') > $settings->get('current_y')) {
                for ($y = $settings->get('current_y'); $y <= $this->argument('y') ; $y+=$loop_increment_value) { 
                    $calculator->setPoint($settings->get('current_x'), $y);
                    echo round($y, 2).'-';
                    $settings->set('current_y', round($y, 2));
                    $primaryHandMover->rotate($calculator->getPrimaryHandAngle());
                    $secondaryHandMover->rotate($calculator->getSecondaryHandAngle());
                }

                return 0;
            }

            if ($this->argument('y') < $settings->get('current_y')) {
                for ($y = $settings->get('current_y'); $y >= $this->argument('y') ; $y-=$loop_increment_value) { 
                    $calculator->setPoint($settings->get('current_x'), $y);
                    echo round($y, 2).'-';
                    $settings->set('current_y', round($y, 2));
                    $primaryHandMover->rotate($calculator->getPrimaryHandAngle());
                    $secondaryHandMover->rotate($calculator->getSecondaryHandAngle());
                }

                return 0;
            }
        }

        if ($this->argument('x') < $settings->get('current_x') ) { 
            for ($x = $settings->get('current_x'); $x >= $this->argument('x') ; $x-=$loop_increment_value) { 
                $y = $path_traverser->getYWhenX($x);
                $calculator->setPoint($x, $y);
                echo round($x, 2).'-';
                echo round($y, 2).'-';
                $settings->set('current_x', round($x, 2));
                $settings->set('current_y', round($y, 2));
                $primaryHandMover->rotate($calculator->getPrimaryHandAngle());
                $secondaryHandMover->rotate($calculator->getSecondaryHandAngle());
            }

            return 0;
        }
        if ($this->argument('x') > $settings->get('current_x') ) { 
            for ($x = $settings->get('current_x'); $x <= $this->argument('x') ; $x+=$loop_increment_value) { 
                $y = $path_traverser->getYWhenX($x);
                $calculator->setPoint($x, $y);
                echo round($x, 2).'-';
                echo round($y, 2).'-';
                $settings->set('current_x', round($x, 2));
                $settings->set('current_y', round($y, 2));
                $primaryHandMover->rotate($calculator->getPrimaryHandAngle());
                $secondaryHandMover->rotate($calculator->getSecondaryHandAngle());
            }

            return 0;
        }
    }
}
