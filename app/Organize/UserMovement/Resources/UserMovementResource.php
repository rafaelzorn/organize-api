<?php

namespace App\Organize\UserMovement\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMovementResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                   => $this->id,
            'movement_category_id' => $this->movementCategory->id,
            'movement_category'    => $this->movementCategory->name,
            'description'          => $this->description,
            'value'                => $this->value,
            'movement_date'        => $this->movement_date,
            'movement_type'        => $this->movement_type,
        ];
    }
}
