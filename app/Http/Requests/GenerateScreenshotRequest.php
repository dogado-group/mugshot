<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\FileInterface;
use App\Contracts\ResponsableInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class GenerateScreenshotRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required', 'url'],
            'width' => ['sometimes', 'integer', 'max:' . config('mugshot.validation.maxWidth')],
            'height' => ['sometimes', 'integer', 'max:' . config('mugshot.validation.maxHeight')],
            'fullPage' => ['sometimes', 'boolean'],
            'deviceScale' => ['sometimes', 'integer', 'between:1,3'],
            'quality' => ['sometimes', 'integer', 'between:30,100'],
            'delay' => ['sometimes', 'integer', 'max:' . config('mugshot.validation.delay')],
            'fileExtension' => [
                'sometimes',
                Rule::in(FileInterface::ALLOWED_SCREENSHOT_EXTENSIONS)
            ],
            'response' => [
                'sometimes',
                Rule::in(ResponsableInterface::ALLOWED_RESPONSES)
            ],
        ];
    }
}
