<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ClosingStoreinReportController;

class ClosingStoreinReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storein:closing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'storein closing every night';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $closing = new ClosingStoreinReportController();
        $closing->closing();
        return Command::SUCCESS;
    }
}
