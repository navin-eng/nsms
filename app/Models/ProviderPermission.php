<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class ProviderPermission extends SpatiePermission
{
    protected $connection = 'provider';
}
