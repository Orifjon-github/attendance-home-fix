<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $advantages
 * @property mixed $image
 * @property mixed $is_view
 * @property mixed $type
 * @property mixed $created_at
 */
class NotificationResource extends JsonResource
{
   use Helpers;
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->getValue($this),
            'short_description' => $this->getValue($this, 'short_description'),
            'image' => env('IMAGES_BASE_URL') . $this->getValue($this, 'image'),
            'type' => $this->type,
            'is_view' => $this->type == 'global' || $this->is_view,
            'date' => date('Y-m-d H:i', strtotime($this->created_at))
        ];
    }
}
