<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Entity\Screenshot as ScreenshotModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Screenshot extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var ScreenshotModel
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
            'id' => $this->resource->getId(),
            'attributes' => [
                ScreenshotModel::ATTRIBUTE_URL => $this->resource->getUrl(),
                ScreenshotModel::ATTRIBUTE_CREATED_AT => $this->resource->getCreatedAt()->toAtomString(),
            ],
        ];
    }
}
