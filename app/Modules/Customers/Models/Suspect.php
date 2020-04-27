<?php

namespace App\Modules\Customers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Suspect extends Model
{
    use Notifiable;
    public static $counter = 0;
    protected $fillable  = [
        'id',
        'concatenated_names',
        'second_name',
        'first_name',
        'third_name',
        'fourth_name',
        'organization',
        'birth_date',
        'others'
    ];

    public function suspiciousCustomers()
    {
        return $this->hasOne(SuspiciousCustomers::class);
    }

}
