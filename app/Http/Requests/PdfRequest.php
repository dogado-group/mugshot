<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\ResponsableInterface;
use Illuminate\Validation\Rule;

class PdfRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
            'response' => ['sometimes', Rule::in(ResponsableInterface::ALLOWED_RESPONSES)],
        ];
    }

    public function content(): string
    {
        return $this->validated('content');
    }

    public function responseType(): string
    {
        return $this->validated('response', ResponsableInterface::INLINE);
    }
}
