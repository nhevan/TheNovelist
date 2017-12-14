<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public function track($hand, $steps_to_track)
    {
    	$hand = $this->where('key', $hand.'_current_step_count')->first();
    	$hand->value += $steps_to_track;

    	$hand->save();
    }
}
