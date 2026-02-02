<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenHas('name'),
            'module' => $this->whenHas('module'),
            'display_name' => $this->when($this->name, function() {
                return str_contains($this->name, '.') ? explode('.', $this->name)[1] : $this->name;
            })
        ];
    }
}
