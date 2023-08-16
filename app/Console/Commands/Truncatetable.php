<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class Truncatetable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tables = ['storein_item','store_out_items','stocks', 'opening_storein_reports','closing_storein_reports','purchase_storein_reports','items_of_storeins']; // List of tables to truncate

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        $this->info('Tables truncated successfully.');
        return Command::SUCCESS;
    }
}
