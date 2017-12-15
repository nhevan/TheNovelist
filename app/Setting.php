<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	/**
	 * tracks the position of the given hand in steps count
	 * @param  [type] $hand           [description]
	 * @param  [type] $steps_to_track [description]
	 * @return [type]                 [description]
	 */
    public function track($hand, $steps_to_track)
    {
    	$hand = $this->where('key', $hand.'_current_step_count')->first();
    	$hand->value += $steps_to_track;

    	$hand->save();
    }

    /**
     * resets the position of the given hand to 0
     * @param  [type] $hand [description]
     * @return [type]       [description]
     */
    public function reset($hand)
    {
    	$hand = $this->where('key', $hand.'_current_step_count')->first();
    	$hand->value = 0;

    	$hand->save();
    }

    /**
     * returns the current angle of the given hand made between the x axis
     * @param  [type] $hand [description]
     * @return [type]       [description]
     */
    public function getCurrentHandAngle($hand)
    {
        $hand = $this->where('key', $hand.'_current_step_count')->first();

        return $hand->value * .087891;
    }
}
