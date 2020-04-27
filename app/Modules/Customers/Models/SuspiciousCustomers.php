<?php

namespace App\Modules\Customers\Models;

use Illuminate\Database\Eloquent\Model;

class SuspiciousCustomers extends Model
{

    protected $fillable = [
        'id',
        'crm_client_id',
        'suspects_id',
        'second_name',
        'first_name',
        'third_name',
        'fourth_name',
        'organization',
        'birth_date',
        'client_registration_date',
        'sim',
        'other'
    ];

    public function suspects()
    {
        return $this->belongsTo(Suspect::class, 'suspects_id', 'id');
    }

}

