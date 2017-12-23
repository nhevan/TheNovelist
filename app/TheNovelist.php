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
    public function goto($x_cord, $y_cord)
    {
    	$secondaryHandMover = new SecondaryHandController;
        $primaryHandMover = new PrimaryHandController;
        $calculator = new AngleCalculator();
        $path_traverser = new PathTraverser();
        $settings = new Setting;
        $loop_increment_value = .01;
        
        $calculator->setPrimaryHandLength(15);
        $calculator->setSecondaryHandLength(10);

        $path_traverser->setX1($settings->get('current_x'));
        $path_traverser->setY1($settings->get('current_y'));
        $path_traverser->setX2($x_cord);
        $path_traverser->setY2($y_cord);

        if($x_cord == $settings->get('current_x')){
            if ($y_cord > $settings->get('current_y')) {
                for ($y = $settings->get('current_y')+$loop_increment_value; $y <= $y_cord ; $y+=$loop_increment_value) { 
                    $calculator->setPoint($settings->get('current_x'), $y);
                    $settings->set('current_y', round($y, 2));
                    $primaryHandMover->rotate($calculator->getPrimaryHandAngle());
                    $secondaryHandMover->rotate($calculator->getSecondaryHandAngle());
                }

                return 0;
            }

            if ($y_cord < $settings->get('current_y')) {
                for ($y = $settings->get('current_y')+$loop_increment_value; $y >= $y_cord ; $y-=$loop_increment_value) { 
                    $calculator->setPoint($settings->get('current_x'), $y);
                    $settings->set('current_y', round($y, 2));
                    $primaryHandMover->rotate($calculator->getPrimaryHandAngle());
                    $secondaryHandMover->rotate($calculator->getSecondaryHandAngle());
                }

                return 0;
            }
        }

        if ($x_cord < $settings->get('current_x') ) { 
            for ($x = $settings->get('current_x'); $x >= $x_cord ; $x-=$loop_increment_value) { 
                $y = $path_traverser->getYWhenX($x);
                $calculator->setPoint($x, $y);
                $settings->set('current_x', round($x, 2));
                $settings->set('current_y', round($y, 2));
                $primaryHandMover->rotate($calculator->getPrimaryHandAngle());
                $secondaryHandMover->rotate($calculator->getSecondaryHandAngle());
            }

            return 0;
        }
        if ($x_cord > $settings->get('current_x') ) { 
            for ($x = $settings->get('current_x'); $x <= $x_cord ; $x+=$loop_increment_value) { 
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
