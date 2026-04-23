<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreCustomJobVacancyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'job_text' => ['required', 'string'],
        ];
    }
}
