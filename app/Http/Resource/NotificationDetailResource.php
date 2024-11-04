<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use App\Models\Seo;
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
class NotificationDetailResource extends JsonResource
{
   use Helpers;
    public function toArray(Request $request): array
    {
        $seo = Seo::where('type', 'service')->where('parent_id', $this->id)->get();
        return [
            'id' => $this->id,
            'title' => $this->getValue($this),
            'short_description' => $this->getValue($this, 'short_description'),
            'description' => $this->getValue($this, 'description'),
            'image' => env('IMAGES_BASE_URL') . $this->getValue($this, 'image'),
            'type' => $this->type,
            'is_view' => $this->type == 'global' || $this->is_view,
            'date' => date('Y-m-d H:i', strtotime($this->created_at)),
            'seo' => SeoResource::collection($seo)
        ];
    }
}
