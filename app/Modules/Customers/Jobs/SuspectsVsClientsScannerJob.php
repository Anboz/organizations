<?php

namespace App\Modules\Customers\Jobs;

use App\Modules\Customers\UseCases\CustomersDiscoverer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SuspectsVsClientsScannerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $suspiciousList;

    public function __construct($suspiciousList)
    {
        $this->suspiciousList = $suspiciousList;
    }

    public function handle()
    {
        $customersDiscoverer = new CustomersDiscoverer();
        $customersDiscoverer->compareSuspectsVsCustomers($this->suspiciousList);
    }
}
