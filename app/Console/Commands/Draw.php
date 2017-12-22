<?php

namespace App\Console\Commands;

use App\TheNovelist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Draw extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'draw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Draws through a given set of coordinates by readind a local text file.';

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
        $novelist = new TheNovelist;

        $file_handle = fopen('storage/input_file_for_draw_command.txt', "r");
        while (!feof($file_handle)) {
           $line = fgets($file_handle);
           $x = explode(' ', $line)[0];
           $y = trim(explode(' ', $line)[1]);
           $novelist->goto($x, $y);
        }
        fclose($file_handle);
    }
}
