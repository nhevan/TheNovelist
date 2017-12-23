<?php

use App\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
	protected $settings;

	public function __construct(Setting $settings)
	{
		$this->settings = $settings;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->newSettings('primary_hand_current_step_count', 0, 'This keeps tracks of all the steps traversed by the Primary Hand. Please not that one Anti-Clockwise step is a positive step.');
        $this->newSettings('secondary_hand_current_step_count', 0, 'This keeps tracks of all the steps traversed by the Secondary Hand.');
        $this->newSettings('current_x', 0, 'Keeps the current x coordinate of the pen head.');
        $this->newSettings('current_y', 0, 'Keeps the current y coordinate of the pen head.');
        $this->newSettings('step_count', 1, 'Motor step count per phase');
        $this->newSettings('min_angle', .087891, 'Motor step count per phase');
    }

    public function newSettings($key, $value, $description = null)
    {
    	$setting = $this->settings->where('key', $key)->first();
        if (!$setting) {
            return factory('App\Setting')->create([
                    'key' => $key,
                    'value' => $value
                ]);
        }
    }
}
