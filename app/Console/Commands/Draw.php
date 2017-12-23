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
    protected $signature = 'draw {file} {loop=1}';

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
        for ($i=0; $i < $this->argument('loop'); $i++) { 
            $this->write();
        }
    }

    public function write($value='')
    {
        $novelist = new TheNovelist;
        $pixel_width = .02;

        $file_handle = fopen('storage/'.$this->argument('file'), "r");
        while (!feof($file_handle)) {
           $line = fgets($file_handle);
           $x = explode(' ', $line)[0];
           $y = trim(explode(' ', $line)[1]);
           $x = $x * $pixel_width;
           $y = $y * $pixel_width;

           echo "X: {$x}; Y: {$y} \r\n";
           $novelist->goto($x, $y);
        }
        fclose($file_handle);
    }
}
