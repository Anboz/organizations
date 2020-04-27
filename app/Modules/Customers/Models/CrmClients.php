<?php

namespace App\Modules\Customers\Models;

use App\Modules\Customers\UseCases\CustomersDiscoverer;
use Illuminate\Database\Eloquent\Model;

class CrmClients extends Model
{
    public static $counter = 0;

    protected $fillable  = [
        'crm_client_id',
        'concatenated_names',
        'second_name',
        'first_name',
        'third_name',
        'fourth_name',
        'birth_date',
        'client_registration_date',
        'comment',
    ];


    protected static function boot()
    {
        parent::boot();

        static::saved(function (CrmClients $crmClient) {
            $useCase = new CustomersDiscoverer();
            $useCase->checkCustomerLegality($crmClient);
        });
    }



}
