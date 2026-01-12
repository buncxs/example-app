<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{

    use HasUuids;

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
