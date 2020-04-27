<?php

namespace App\Modules\Customers\Models;

use Illuminate\Database\Eloquent\Model;


class SuspiciousOrganizations extends Model
{

    protected $fillable = [
        'id',
        'concatenated_name',
        'organization_name',
        'list_type',
        'comment',
        'address',
        'alias',
        'others'
    ];
}

