<?php

namespace App\Console\Commands;

use App\Modules\Customers\UseCases\CustomersDiscoverer;
use Illuminate\Console\Command;

class SuspectsVsClientsScanner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        CustomersDiscoverer::rescanSuspectsVsCustomers();
    }
}
