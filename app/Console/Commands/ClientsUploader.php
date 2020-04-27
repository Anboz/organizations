<?php

namespace App\Console\Commands;

use App\Modules\Customers\Models\CrmClients;
use App\Modules\Customers\UseCases\CustomersDiscoverer;
use Illuminate\Console\Command;

class ClientsUploader extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:upload';

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
        CrmClients::truncate();

        $handle = fopen(@public_path() . "/clients-list.csv", "r");

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {

            $useCase = new CustomersDiscoverer();
            $client = $this->dropNonInitialData($data);

            $useCase->addClientToDB($client);

        }
        fclose($handle);

        return 'finished';
    }

    public function dropNonInitialData($data)
    {
        $i = 0;
        foreach ($data as $d) {
            switch ($d) {
                case strlen($d) > 22: $data[$i] = ''; break;
                case $d == 'null': $data[$i] = ''; break;
                case $d == 'UnknownSURNAME': $data[$i] = ''; break;
                case $d == 'UnknownNAME': $data[$i] = ''; break;
                case $d == 'NULL': $data[$i] = ''; break;
            }
            $i++;
        }
        return $data;
    }
}
