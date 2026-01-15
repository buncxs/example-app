<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Permission as SpatieRole;
use Spatie\Permission\Traits\HasPermissions;

class Permission extends SpatieRole
{
    use HasUuids, HasPermissions;

    public function uniqueIds(): array
    {
        return ['uuid']; 
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

}
