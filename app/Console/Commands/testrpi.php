<?php

namespace App\Console\Commands;

use PiPHP\GPIO\GPIO;
use Illuminate\Console\Command;
use PiPHP\GPIO\Pin\PinInterface;

class testrpi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testrpi {x} {y}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test that piphp/gpio is working';

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
        echo "Testing RPi using piphp/gpio";
        $x = $this->argument('x');
        $y = $this->argument('y');
        $product = $x * $y;
        echo "product is {$product}";
    }
}
