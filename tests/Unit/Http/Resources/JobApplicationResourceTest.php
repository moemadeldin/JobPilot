<?php

declare(strict_types=1);
use App\Http\Resources\JobApplicationResource;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Request;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->company = Company::factory()->create(['user_id' => $this->user->id]);
    $this->jobVacancy = JobVacancy::factory()->for($this->company)->create([
        'title' => 'Backend Developer',
        'description' => 'We are looking for a Laravel developer',
    ]);
    $this->resume = Resume::factory()->for($this->user)->create([
        'extracted_text' => 'Experienced Laravel developer',
    ]);
});

describe('JobApplicationResource', function (): void {
    it('transforms application with array feedback', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->for($this->resume)->create([
            'feedback' => ['strengths' => ['PHP'], 'weaknesses' => ['Communication']],
            'compatibility_score' => 85.5,
            'reviewed_at' => now(),
        ]);

        $resource = new JobApplicationResource($application);
        $array = $resource->toArray(new Request());

        expect($array['evaluation']['feedback']['strengths'])->toContain('PHP');
        expect($array['evaluation']['compatibility_score'])->toBe(85.5);
    });

    it('transforms application with string feedback', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->for($this->resume)->create([
            'feedback' => '{"strengths":["PHP"],"weaknesses":["Communication"]}',
            'compatibility_score' => 75.0,
            'reviewed_at' => now(),
        ]);

        $resource = new JobApplicationResource($application);
        $array = $resource->toArray(new Request());

        expect($array['evaluation']['feedback']['strengths'])->toContain('PHP');
        expect($array['evaluation']['compatibility_score'])->toBe(75.0);
    });

    it('handles null compatibility score', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->for($this->resume)->create([
            'compatibility_score' => null,
            'reviewed_at' => null,
        ]);

        $resource = new JobApplicationResource($application);
        $array = $resource->toArray(new Request());

        expect($array['evaluation']['compatibility_score'])->toBeNull();
        expect($array['evaluation']['reviewed_at'])->toBeNull();
    });
});
