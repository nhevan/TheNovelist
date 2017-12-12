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
    protected $signature = 'testrpi';

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
        // Create a GPIO object
        $gpio = new GPIO();

        // Retrieve pin 18 and configure it as an output pin
        $pin = $gpio->getOutputPin(18);

        // Set the value of the pin high (turn it on)
        $pin->setValue(PinInterface::VALUE_HIGH);
        usleep(2000000); // sleep for 2 seconds
        $pin->setValue(PinInterface::VALUE_LOW);
    }
}
