<?php

namespace App\Console\Commands;

use App\Setting;
use Illuminate\Console\Command;

class Reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'resets the settings table.';

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
        $setting = new Setting;
        
        $setting->set('primary_hand_current_step_count', 0);
        $setting->set('secondary_hand_current_step_count', 0);
        $setting->set('current_x', 15);
        $setting->set('current_y', 10);
    }
}
