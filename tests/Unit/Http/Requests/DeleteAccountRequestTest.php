<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\DeleteAccountRequest;

test('rules require password', function (): void {
    $request = new DeleteAccountRequest();
    $rules = $request->rules();

    expect($rules)->toHaveKey('password');
    expect($rules['password'])->toContain('required');
});
