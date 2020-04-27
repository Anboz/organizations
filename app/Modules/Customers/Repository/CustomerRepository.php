<?php

namespace App\Modules\Customers\Repository;

use App\Modules\Customers\Jobs\SuspiciousClientNotification;
use DB;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository
{
    private $initials;
    private $operationType;
    private $apiToken;
    private $data = [];
    private $discoveredList;


    public function __construct($attributes)
    {
        $this->initials = $attributes['initials'];
        $this->operationType = $attributes['operation_type'];
        $this->apiToken = $attributes['api_token'];
        $this->discoveredList = new Collection;
    }

    public function examineCustomerByInitials(): array
    {

        foreach (makeListOfWordCombinations($this->initials) as $word) {
            $fetchedData = DB::select("
	            select tab.id, tab.first_name, tab.second_name, tab.third_name, tab.fourth_name, tab.organization, tab.birth_date, tab.sim, tab.created_at
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
                $this->discoveredList->add($f);
            }
        }

        $distinctListOfSuspects = $this->discoveredList->unique('id');

        foreach ($distinctListOfSuspects->toArray() as $suspect) {
            if (!empty($suspect)) {
                $this->data['suspect'] = true;
                $this->data['suspect_list'] = $distinctListOfSuspects;
                $this->data['max_sim'] = $distinctListOfSuspects[0]->sim;

                if (((float)$suspect->sim) > 0.9) {
                    $message = makeMessage($suspect, $this->operationType, $this->initials);
                    SuspiciousClientNotification::dispatch((string)$message)->delay(now()->addSecond(5));
                }

                return $this->data;
            }
        }

        $this->data['suspect'] = false;

        return $this->data;
    }

    public function examineAllyOrganization(): array
    {
        foreach (makeListOfWordCombinations($this->initials) as $word) {
            $fetchedData = DB::select("
	            select tab.id, tab.organization_name, tab.list_type, tab.comment, tab.address, tab.alias, tab.others, tab.sim, tab.created_at
	             from (select 
	            id, 
                organization_name, 
                list_type,
                comment,
                address,
                alias,
                others,
                created_at,
                similarity(concatenated_name, :word) as sim 
                from suspicious_organizations
                where concatenated_name % :word) as tab
                order by tab.sim desc;
            ", array('word' => $word));
            foreach ($fetchedData as $f) {
                $this->discoveredList->add($f);
            }
        }

        $discoveredOrganizations = $this->discoveredList->unique('id');

        if (!empty($discoveredOrganizations->toArray())) {
            $this->data['suspect'] = true;
            $this->data['suspect_list'] = $discoveredOrganizations->toArray();
            $this->data['max_sim'] = $discoveredOrganizations[0]->sim;
            return $this->data;
        }

        $this->data['suspect'] = false;

        return $this->data;
    }

}
