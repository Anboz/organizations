<?php

namespace App\Modules\Customers\UseCases;

use App\Exceptions\LogData;
use App\Modules\Customers\Jobs\IPSuspectsVsClientsScannerJob;
use App\Modules\Customers\Jobs\MIASuspectsVsClientsScannerJob;
use App\Modules\Customers\Jobs\SuspectsVsClientsScannerJob;
use App\Modules\Customers\Jobs\SuspiciousClientNotification;
use App\Modules\Customers\Jobs\UNSuspectsVsClientsScannerJob;
use App\Modules\Customers\Models\CrmClients;
use App\Modules\Customers\Models\Suspect;
use App\Modules\Customers\Models\SuspiciousCustomers;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CustomersDiscoverer
{
    public function addClientToDB(array $row)
    {
        echo Suspect::$counter++;
        echo "\n";

        $client = new CrmClients();

        $client->crm_client_id = $row[0];
        $client->concatenated_names = concatinateInitials($row[2], $row[1], $row[3], '');
        $client->second_name = $row[2];
        $client->first_name = $row[1];
        $client->third_name = $row[3];
        $client->client_registration_date = $row[4];
        $client->birth_date = $row[5];

        $client->save();
    }

    public function compareSuspectsVsCustomers($suspiciousList)
    {
      foreach ($suspiciousList as $suspect) {

            $initials = getInitinals($suspect);

            echo CrmClients::$counter++;
            echo "\n";

            foreach (makeListOfWordCombinations($initials) as $word) {
                $listOfDiscoveredSuspiciousClients = new Collection;
                $fetchedData = DB::select("
	                    select  tab.crm_client_id, tab.first_name, tab.second_name, tab.third_name, tab.fourth_name, tab.birth_date, tab.client_registration_date, tab.sim
	                    from (select
	                            crm_client_id,
	                           first_name,
                                second_name,
                                third_name,
                                fourth_name,
                                birth_date,
                                client_registration_date,
                                similarity(concatenated_names, :word) as sim
                                from crm_clients
                                where concatenated_names % :word) as tab
                                order by sim desc;
                    ", array('word' => $word));

                foreach ($fetchedData as $f) {
                    $listOfDiscoveredSuspiciousClients->add($f);
                }

                $distinctListOfSuspiciousClients = $listOfDiscoveredSuspiciousClients->unique('id');

                if (!empty($distinctListOfSuspiciousClients)) {
                    $this->saveDiscoveredDataToDB($distinctListOfSuspiciousClients, $suspect,
                        $this->getRequestedSuspectInitials($suspect));
                }
            }
        }
    }

    private function saveDiscoveredDataToDB($distinctListOfSuspiciousClients, $suspect, $requestedName)
    {
        foreach ($distinctListOfSuspiciousClients->toArray() as $client) {
            if (!empty($client)) {
                if (((float)$client->sim) > 0.6) {
                    $attributes = (array)$client;
                    $attributes['suspects_id'] = $suspect->id;
                    $attributes['organization'] = $suspect->organization;

                    try {
                        $suspiciousCustomer = new SuspiciousCustomers($attributes);

                        if (((float)$suspect->sim) > 0.9) {
                            $message = makeMessage($suspiciousCustomer, 'from scanning comparing Suspects Vs Customers',
                                $requestedName);
                            SuspiciousClientNotification::dispatch((string)$message)->delay(now()->addSecond(5));
                        }

                        $suspiciousCustomer->save();
                    } catch (Exception $e) {
                        throw new LogData($e);
                    }
                }
            }
        }
    }

    private function getRequestedSuspectInitials($suspect): string
    {
        return $suspect->second_name . ' ' . $suspect->first_name . ' ' . $suspect->third_name . ' ' . $suspect->fourth_name;
    }

    public function compareClientVsSuspectsList()
    {
        SuspiciousCustomers::truncate();

        foreach (CrmClients::all() as $client) {

            $initials = getInitinals($client);

            echo CrmClients::$counter++;
            echo "\n";

            foreach (makeListOfWordCombinations($initials) as $word) {

                $listOfDiscoveredClients = new Collection;

                $fetchedData = DB::select("
	                    select tab.id, tab.first_name, tab.second_name, tab.third_name, 
	                    tab.fourth_name, tab.organization, tab.birth_date, tab.sim, tab.created_at
	                    from (select
	                            id,
                                first_name,
                                second_name,
                                third_name,
                                fourth_name,
                                organization,
                                birth_date,
                                created_at,
                                similarity(concatenated_names, :word) as sim
                                from suspects
                                where concatenated_names % :word) as tab
                                order by tab.sim desc;
                    ", array('word' => $word));

                foreach ($fetchedData as $f) {
                    $listOfDiscoveredClients->add($f);
                }

                $distinctListOfClients = $listOfDiscoveredClients->unique('id');

                $attributes = [];
                $attributes['crm_client_id'] = $client->crm_client_id;
                $attributes['second_name'] = $client->second_name;
                $attributes['first_name'] = $client->first_name;
                $attributes['third_name'] = $client->third_name;
                $attributes['fourth_name'] = $client->fourth_name;
                $attributes['birth_date'] = $client->birth_date;
                $attributes['client_registration_date'] = $client->client_registration_date;

                $this->saveDiscoveredClientToDB($distinctListOfClients, $attributes, $initials);
            }
        }
    }

    private function saveDiscoveredClientToDB($distinctListOfSuspects, $attributes, $requestedName)
    {
        foreach ($distinctListOfSuspects->toArray() as $suspect) {
            if (!empty($suspect)) {
                if (((float)$suspect->sim) > 0.6) {
                    try {
                        $attributes['suspects_id'] = $suspect->id;
                        $attributes['organization'] = $suspect->organization;
                        $attributes['sim'] = $suspect->sim;
                        $suspiciousCustomer = new SuspiciousCustomers($attributes);

                        if (((float)$suspect->sim) > 0.9) {
                            $message = makeMessage($suspiciousCustomer, 'scanning client vs suspects', $requestedName);
                            SuspiciousClientNotification::dispatch((string)$message)->delay(now()->addSecond(5));
                        }
                        $suspiciousCustomer->save();

                    } catch (Exception $e) {
                        dd($e);
                        //   throw new LogData($e);
                    }
                }

            }
        }
    }

    public function checkCustomerLegality(CrmClients $client)
    {
        $initials = getInitinals($client);

        foreach (makeListOfWordCombinations($initials) as $word) {

            $listOfDiscoveredClients = new Collection;

            $fetchedData = DB::select("
	                    select tab.id, tab.first_name, tab.second_name, tab.third_name, 
	                    tab.fourth_name, tab.organization, tab.birth_date, tab.sim, tab.created_at
	                    from (select
	                            id,
                                first_name,
                                second_name,
                                third_name,
                                fourth_name,
                                organization,
                                birth_date,
                                created_at,
                                similarity(concatenated_names, :word) as sim
                                from suspects
                                where concatenated_names % :word) as tab
                                order by tab.sim desc;
                    ", array('word' => $word));

            foreach ($fetchedData as $f) {
                $listOfDiscoveredClients->add($f);
            }

            $distinctListOfClients = $listOfDiscoveredClients->unique('id');

            $attributes = [];

            $attributes['crm_client_id'] = $client->crm_client_id;
            $attributes['second_name'] = $client->second_name;
            $attributes['first_name'] = $client->first_name;
            $attributes['third_name'] = $client->third_name;
            $attributes['fourth_name'] = $client->fourth_name;
            $attributes['client_registration_date'] = $client->client_registration_date;
            $attributes['birth_date'] = $client->birth_date;

            $this->saveDiscoveredClientToDB($distinctListOfClients, $attributes,
                $this->getRequestedClientInitials($client));
        }
    }

    private function getRequestedClientInitials($client): string
    {
        return $client->second_name . ' ' . $client->first_name . ' ' . $client->third_name;
    }

    public static function rescanSuspectsVSCustomers()
    {
        SuspiciousCustomers::truncate();
        $suspects =  Suspect::all();
        SuspectsVsClientsScannerJob::dispatch($suspects)->delay(now()->addSecond(5));
    }

    public static function rescanMIASuspectsVSCustomers()
    {
        SuspiciousCustomers::where('organization', 'MIA')->delete();
        $MIASuspects =  Suspect::where('organization', 'MIA')->get();
        MIASuspectsVsClientsScannerJob::dispatch($MIASuspects)->delay(now()->addSecond(5));
    }

    public static function rescanUNSuspectsVSCustomers()
    {
        SuspiciousCustomers::where('organization', 'UN')->delete();
        $UNSuspects =  Suspect::where('organization', 'UN')->get();
        UNSuspectsVsClientsScannerJob::dispatch($UNSuspects)->delay(now()->addSecond(5));
    }

    public static function rescanIPSuspectsVSCustomers()
    {
        SuspiciousCustomers::where('organization', 'IP')->delete();
        $IPSuspects =  Suspect::where('organization', 'IP')->get();
        IPSuspectsVsClientsScannerJob::dispatch($IPSuspects)->delay(now()->addSecond(5));
    }

}
