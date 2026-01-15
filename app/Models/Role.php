<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasPermissions;


class Role extends SpatieRole
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
