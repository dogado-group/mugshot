<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\ResponableInterface;
use Illuminate\Validation\Rule;

class GeneratePdfRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'content' => ['required'],
            'response' => [
                'sometimes',
                Rule::in(ResponableInterface::ALLOWED_RESPONSES)
            ],
        ];
    }
}
