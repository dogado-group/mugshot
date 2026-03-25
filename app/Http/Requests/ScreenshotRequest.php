<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\FileInterface;
use App\Contracts\ResponsableInterface;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class ScreenshotRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required', 'url:http,https', 'active_url'],
            'width' => ['sometimes', 'integer', 'min:1', 'max:'.config('mugshot.validation.maxWidth')],
            'height' => ['sometimes', 'integer', 'min:1', 'max:'.config('mugshot.validation.maxHeight')],
            'fullPage' => ['sometimes', 'boolean'],
            'deviceScale' => ['sometimes', 'integer', 'between:1,3'],
            'quality' => ['sometimes', 'integer', 'between:30,100'],
            'delay' => ['sometimes', 'integer', 'min:0', 'max:'.config('mugshot.validation.maxDelay')],
            'fileExtension' => ['sometimes', Rule::in(FileInterface::ALLOWED_SCREENSHOT_EXTENSIONS)],
            'response' => ['sometimes', Rule::in(ResponsableInterface::ALLOWED_RESPONSES)],
        ];
    }

    public function url(): string
    {
        return $this->validated('url');
    }

    public function responseType(): string
    {
        return $this->validated('response', ResponsableInterface::INLINE);
    }

    /** @return Collection<string, mixed> */
    public function parameters(): Collection
    {
        return Collection::make(
            $this->validated()
        )->only('width', 'height', 'fullPage', 'deviceScale', 'quality', 'delay', 'fileExtension');
    }
}
