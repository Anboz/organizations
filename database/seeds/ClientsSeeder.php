<?php

use App\Clients;
use Illuminate\Database\Seeder;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $handle = fopen(@public_path() . "/suspects-list.csv", "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            Clients::addClient($data);
        }
        fclose($handle);

    }
}
