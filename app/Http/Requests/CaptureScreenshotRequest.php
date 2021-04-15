<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\FileInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class CaptureScreenshotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (App::environment('local')) {
            return true;
        }

        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url' => ['required', 'url'],
            'width' => ['sometimes', 'integer', 'max:' . config('cloudshot.validation.maxWidth')],
            'height' => ['sometimes', 'integer', 'max:' . config('cloudshot.validation.maxHeight')],
            'fullPage' => ['sometimes', 'boolean'],
            'deviceScale' => ['sometimes', 'integer', 'between:1,3'],
            'quality' => ['sometimes', 'integer', 'between:30,100'],
            'delay' => ['sometimes', 'integer', 'max:' . config('cloudshot.validation.delay')],
            'fileExtension' => [
                'sometimes',
                Rule::in(FileInterface::ALLOWED_SCREENSHOT_EXTENSIONS)
            ],
            'response' => [
                'sometimes',
                Rule::in(['inline', 'download', 'json'])
            ],
        ];
    }
}
