<?php

declare(strict_types=1);

use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

it('can upload resume', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $resumeFile = UploadedFile::fake()->create('resume.pdf', 200, 'application/pdf');

    $response = $this->post(route('resumes.store'), [
        'path' => $resumeFile,
    ]);

    $response->assertStatus(Response::HTTP_CREATED);

    $resume = Resume::query()->first();

    Storage::disk('public')->assertExists($resume->path);

    $this->assertDatabaseHas('resumes', [
        'user_id' => $user->id,
        'path' => $resume->path,
    ]);
});
