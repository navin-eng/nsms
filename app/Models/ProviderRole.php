<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class ProviderRole extends SpatieRole
{
    protected $connection = 'provider';
}
