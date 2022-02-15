<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Entity\Screenshot as ScreenshotModel;

class Screenshot extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var \App\Entity\Screenshot
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'screenshot',
            'id' => $this->resource->getId(),
            'attributes' => [
                ScreenshotModel::ATTRIBUTE_URL => $this->resource->getUrl(),
                ScreenshotModel::ATTRIBUTE_CREATED_AT => $this->resource->getCreatedAt()->toAtomString()
            ]
        ];
    }
}
