<?php

namespace App\Modules\Customers\Jobs;

use App\Modules\Customers\UseCases\CustomersDiscoverer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IPSuspectsVsClientsScannerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $IPSuspects;

    public function __construct($IPSuspects)
    {
        $this->IPSuspects = $IPSuspects;
    }

    public function handle()
    {
        $customersDiscoverer = new CustomersDiscoverer();
        $customersDiscoverer->compareSuspectsVsCustomers($this->IPSuspects);
    }
}
