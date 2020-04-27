<?php

namespace App\Modules\Customers\Models;

use Illuminate\Database\Eloquent\Model;

class WebService extends Model
{
    protected $fillable  = [
        'name',
        'api_token',
    ];
}
