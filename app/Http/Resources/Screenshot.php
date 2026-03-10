<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\DataTransferObject\ScreenshotData;
use Illuminate\Http\Resources\Json\JsonResource;

class Screenshot extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var ScreenshotData
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'type' => 'screenshot',
            'id' => $this->resource->id,
            'attributes' => [
                ScreenshotData::ATTRIBUTE_URL => $this->resource->url,
                ScreenshotData::ATTRIBUTE_CREATED_AT => $this->resource->createdAt->toAtomString(),
            ],
        ];
    }
}
