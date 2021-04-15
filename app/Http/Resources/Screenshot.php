<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Entity\Screenshot as ScreenshotModel;

class Screenshot extends JsonResource
{
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
            'id' => $this->getId(),
            'attributes' => [
                ScreenshotModel::ATTRIBUTE_URL => $this->getUrl(),
                ScreenshotModel::ATTRIBUTE_CREATED_AT => $this->getCreatedAt()->toAtomString()
            ]
        ];
    }
}
