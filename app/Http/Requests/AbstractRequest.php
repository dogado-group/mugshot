<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class AbstractRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (App::environment('local')) {
            return true;
        }

        return auth()->check();
    }
    /**
     * @return array<mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
