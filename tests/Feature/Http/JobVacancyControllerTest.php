<?php

declare(strict_types=1);

use App\Enums\EmploymentType;
use App\Enums\Roles;
use App\Enums\Status;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

it('can create a job vacancy', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    $jobCategory = JobCategory::factory()->create(['name' => 'Tech']);
    $company = Company::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('admin.job-vacancies.store'), JobVacancy::factory()->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $company->id,
    ])->toArray());

    $response->assertStatus(Response::HTTP_CREATED);

});
it('owner can create a job vacancy', function (): void {
    $user = User::factory()->create();
    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);

    $jobCategory = JobCategory::factory()->create(['name' => 'Tech']);
    $company = Company::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('owner.job-vacancies.store'), JobVacancy::factory()->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $company->id,
    ])->toArray());

    $response->assertStatus(Response::HTTP_CREATED);

});
it('can see a job vacancy', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    $jobVacancy = JobVacancy::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('admin.job-vacancies.show', $jobVacancy));

    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            'job' => [
                'id',
                'job_category_id',
                'company_id',
                'title',
                'description',
                'location',
                'expected_salary',
                'employment_type',
                'status',
                'responsibilities',
                'requirements',
                'skills_required',
                'experience_years_min',
                'experience_years_max',
                'nice_to_have',
            ],
        ],
    ]);
    $response->assertJson([
        'data' => [
            'job' => [
                'id' => $jobVacancy->id,
                'job_category_id' => $jobVacancy->job_category_id,
                'company_id' => $jobVacancy->company_id,
                'title' => $jobVacancy->title,
                'description' => $jobVacancy->description,
                'location' => $jobVacancy->location,
                'expected_salary' => $jobVacancy->expected_salary,
                'employment_type' => $jobVacancy->employment_type->label(),
                'status' => $jobVacancy->is_active->label(),
                'responsibilities' => $jobVacancy->responsibilities,
                'requirements' => $jobVacancy->requirements,
                'skills_required' => $jobVacancy->skills_required,
                'experience_years_min' => $jobVacancy->experience_years_min,
                'experience_years_max' => $jobVacancy->experience_years_max,
                'nice_to_have' => $jobVacancy->nice_to_have,
            ],
        ],
    ]);
});
it('can update a job vacancy', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    $jobCategory = JobCategory::factory()->create(['name' => 'Tech']);
    $company = Company::factory()->create();

    $jobVacancy = JobVacancy::factory()->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $company->id,
    ]);
    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(route('admin.job-vacancies.update', $jobVacancy), [
        'title' => 'Frontend React Developer',
    ]);

    $response->assertOk();
    $jobVacancy->refresh();
    expect($jobVacancy->title)->toBe('Frontend React Developer');

});
it('owner can update a job vacancy', function (): void {
    $user = User::factory()->create();
    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);

    $jobCategory = JobCategory::factory()->create(['name' => 'Tech']);
    $company = Company::factory()->create([
        'user_id' => $user->id,
    ]);

    $jobVacancy = JobVacancy::factory()->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $company->id,
    ]);
    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(route('owner.job-vacancies.update', $jobVacancy), [
        'title' => 'Frontend React Developer',
    ]);

    $response->assertOk();
    $jobVacancy->refresh();
    expect($jobVacancy->title)->toBe('Frontend React Developer');

});
it('can delete a job vacancy', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);
    $jobVacancy = JobVacancy::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(route('admin.job-vacancies.destroy', $jobVacancy));

    $response->assertNoContent();

});
it('owner can delete a job vacancy', function (): void {
    $user = User::factory()->create();
    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);
    $company = Company::factory()->create([
        'user_id' => $user->id,
    ]);
    $jobVacancy = JobVacancy::factory()->create([
        'company_id' => $company->id,
    ]);
    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(route('owner.job-vacancies.destroy', $jobVacancy));

    $response->assertNoContent();

});

it('owner can view only his job vacancies', function (): void {
    $userAdmin = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $userAdmin->roles()->attach($adminRole->id);

    $userOwner = User::factory()->create();
    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
    $userOwner->roles()->attach($ownerRole->id);

    $jobCategory = JobCategory::factory()->create(['name' => 'Tech']);
    $companyAdmin = Company::factory()->create([
        'user_id' => $userAdmin->id,
    ]);
    $companyOwner = Company::factory()->create([
        'user_id' => $userOwner->id,
    ]);

    $jobVacancyAdmin = JobVacancy::factory()->count(5)->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $companyAdmin->id,
    ]);
    $jobVacancyOwner = JobVacancy::factory()->count(3)->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $companyOwner->id,
    ]);
    Sanctum::actingAs($userOwner, ['*']);
    $response = $this->getJson(route('owner.job-vacancies.index'));

    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'job' => [
                    'id',
                    'job_category_id',
                    'company_id',
                    'title',
                    'description',
                    'location',
                    'expected_salary',
                    'employment_type',
                    'status',
                    'responsibilities',
                    'requirements',
                    'skills_required',
                    'experience_years_min',
                    'experience_years_max',
                    'nice_to_have',
                ],
            ],
        ],
    ])->assertJsonCount(3, 'data');

    $response->assertJsonFragment([
        'company_id' => $companyOwner->id,
    ]);
});
it('user can create a job vacancy', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('admin.job-vacancies.store'), [
        'name' => 'Tech',
    ]);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('return all jobs without filters', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    $jobCategory = JobCategory::factory()->create(['name' => 'Tech']);
    $company = Company::factory()->create();

    $jobVacancy = JobVacancy::factory()->count(10)->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $company->id,
    ]);
    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('admin.job-vacancies.index'));

    $response->assertOk()
        ->assertJsonCount(6, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'job' => [
                        'id',
                        'job_category_id',
                        'company_id',
                        'title',
                        'description',
                        'location',
                        'expected_salary',
                        'employment_type',
                        'status',
                        'responsibilities',
                        'requirements',
                        'skills_required',
                        'experience_years_min',
                        'experience_years_max',
                        'nice_to_have',
                    ],
                ],
            ],
        ]);
});
it('return all jobs filtered by job_category_id', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    $techJobCategory = JobCategory::factory()->create(['name' => 'Tech']);
    $designJobCategory = JobCategory::factory()->create(['name' => 'Design']);
    $company = Company::factory()->create();

    $techJobVacancy = JobVacancy::factory()->count(5)->create([
        'job_category_id' => $techJobCategory->id,
        'company_id' => $company->id,
    ]);
    $designJobVacancy = JobVacancy::factory()->count(5)->create([
        'job_category_id' => $designJobCategory->id,
        'company_id' => $company->id,
    ]);
    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('admin.job-vacancies.index', [
        'job_category_id' => $techJobCategory->id,
    ]));

    $response->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'job' => [
                        'id',
                        'job_category_id',
                        'company_id',
                        'title',
                        'description',
                        'location',
                        'expected_salary',
                        'employment_type',
                        'status',
                        'responsibilities',
                        'requirements',
                        'skills_required',
                        'experience_years_min',
                        'experience_years_max',
                        'nice_to_have',
                    ],
                ],
            ],
        ]);
    $returnedIds = collect($response->json('data'))
        ->pluck('job.job_category_id')
        ->filter()
        ->map(fn ($id): string => (string) $id)
        ->unique()
        ->values();
    expect($returnedIds)->toContain($techJobCategory->id);
});
it('return all jobs filtered by status', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    $jobCategory = JobCategory::factory()->create(['name' => 'Tech']);
    $company = Company::factory()->create();

    $activeJobVacancy = JobVacancy::factory()->count(5)->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $company->id,
        'is_active' => Status::ACTIVE->value,
    ]);
    $inactiveJobVacancy = JobVacancy::factory()->count(5)->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $company->id,
        'is_active' => Status::INACTIVE->value,
    ]);
    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('admin.job-vacancies.index', [
        'is_active' => Status::ACTIVE->value,
    ]));

    $response->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'job' => [
                        'id',
                        'job_category_id',
                        'company_id',
                        'title',
                        'description',
                        'location',
                        'expected_salary',
                        'employment_type',
                        'status',
                        'responsibilities',
                        'requirements',
                        'skills_required',
                        'experience_years_min',
                        'experience_years_max',
                        'nice_to_have',
                    ],
                ],
            ],
        ]);
    $returnedStatuses = collect($response->json('data'))
        ->pluck('job.status')
        ->values();

    expect($returnedStatuses)->toHaveCount(5);
    expect($returnedStatuses->first())->toBe('Active');
});
it('return all jobs filtered by employment type', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    $jobCategory = JobCategory::factory()->create(['name' => 'Tech']);
    $company = Company::factory()->create();

    $remotelyJobVacancy = JobVacancy::factory()->count(5)->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $company->id,
        'employment_type' => EmploymentType::REMOTELY,
    ]);
    $hybridJobVacancy = JobVacancy::factory()->count(5)->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $company->id,
        'employment_type' => EmploymentType::HYBRID,
    ]);
    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('admin.job-vacancies.index', [
        'employment_type' => EmploymentType::REMOTELY->value,
    ]));

    $response->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'job' => [
                        'id',
                        'job_category_id',
                        'company_id',
                        'title',
                        'description',
                        'location',
                        'expected_salary',
                        'employment_type',
                        'status',
                        'responsibilities',
                        'requirements',
                        'skills_required',
                        'experience_years_min',
                        'experience_years_max',
                        'nice_to_have',
                    ],
                ],
            ],
        ]);
    $returnedStatuses = collect($response->json('data'))
        ->pluck('job.employment_type')
        ->values();

    expect($returnedStatuses)->toHaveCount(5);
    expect($returnedStatuses->first())->toBe('Remote');
});
it('return all jobs filtered by location', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    $jobCategory = JobCategory::factory()->create(['name' => 'Tech']);
    $company = Company::factory()->create();

    $usJobVacancy = JobVacancy::factory()->count(5)->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $company->id,
        'location' => 'USA',
    ]);
    $canadaJobVacancy = JobVacancy::factory()->count(5)->create([
        'job_category_id' => $jobCategory->id,
        'company_id' => $company->id,
        'location' => 'CA',
    ]);
    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('admin.job-vacancies.index', [
        'location' => 'USA',
    ]));

    $response->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'job' => [
                        'id',
                        'job_category_id',
                        'company_id',
                        'title',
                        'description',
                        'location',
                        'expected_salary',
                        'employment_type',
                        'status',
                        'responsibilities',
                        'requirements',
                        'skills_required',
                        'experience_years_min',
                        'experience_years_max',
                        'nice_to_have',
                    ],
                ],
            ],
        ]);
    $returnedStatuses = collect($response->json('data'))
        ->pluck('job.location')
        ->values();

    expect($returnedStatuses)->toHaveCount(5);
    expect($returnedStatuses->first())->toBe('USA');
});
