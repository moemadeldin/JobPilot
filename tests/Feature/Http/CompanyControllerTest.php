<?php

declare(strict_types=1);

use App\Enums\Roles;
use App\Enums\Status;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

it('admin can create a company', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('admin.companies.store'), [
        'user_id' => $user->id,
        'name' => 'Vortex Cloud Solutions',
        'slug' => 'vortex-cloud-solutions',
        'industry' => 'Cloud Computing',
        'address' => '880 Cirrus Lane, Suite 505, Miami, FL 33101',
        'website' => 'www.vortexcloud.io',
        'is_active' => Status::ACTIVE->value,
    ]);
    $response->assertStatus(Response::HTTP_CREATED);

});
it('owner can create a company', function (): void {
    $user = User::factory()->create();
    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('owner.companies.store'), [
        'user_id' => $user->id,
        'name' => 'Vortex Cloud Solutions',
        'slug' => 'vortex-cloud-solutions',
        'industry' => 'Cloud Computing',
        'address' => '880 Cirrus Lane, Suite 505, Miami, FL 33101',
        'website' => 'www.vortexcloud.io',
        'is_active' => Status::ACTIVE->value,
    ]);
    $response->assertStatus(Response::HTTP_CREATED);

});
it('admin can update a company', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    $company = Company::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(route('admin.companies.update', $company), [
        'user_id' => $user->id,
        'name' => 'Vortex Cloud Solutions',
        'slug' => 'vortex-cloud-solutions',
        'industry' => 'Cloud Computing',
        'address' => '880 Cirrus Lane, Suite 505, Miami, FL 33101',
        'website' => 'www.vortexcloud.io',
        'is_active' => Status::ACTIVE->value,
    ]);
    $response->assertOk();

});
it('admin can delete a company', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    $company = Company::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(route('admin.companies.destroy', $company));
    $company->refresh();
    $response->assertNoContent();
    expect($company->trashed())->toBeTrue();
});
it('owner can update his company', function (): void {
    $user = User::factory()->create();
    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);

    $company = Company::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(route('owner.companies.update', $company), [
        'name' => 'Vortex Cloud Solutions',
        'slug' => 'vortex-cloud-solutions',
        'industry' => 'Cloud Computing',
        'address' => '880 Cirrus Lane, Suite 505, Miami, FL 33101',
        'website' => 'www.vortexcloud.io',
        'is_active' => Status::ACTIVE->value,
    ]);
    $response->assertOk();
});
it('owner can delete his company', function (): void {
    $user = User::factory()->create();
    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);

    $company = Company::factory()->create([
        'user_id' => $user->id,
    ]);
    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(route('owner.companies.destroy', $company));
    $company->refresh();
    $response->assertNoContent();
    expect($company->trashed())->toBeTrue();
});
it('user can create a company', function (): void {
    $user = User::factory()->create();
    $userRole = Role::factory()->create(['name' => Roles::USER->value]);
    $user->roles()->attach($userRole->id);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('admin.companies.store'), [
        'user_id' => $user->id,
        'name' => 'Vortex Cloud Solutions',
        'slug' => 'vortex-cloud-solutions',
        'industry' => 'Cloud Computing',
        'address' => '880 Cirrus Lane, Suite 505, Miami, FL 33101',
        'website' => 'www.vortexcloud.io',
        'is_active' => Status::ACTIVE->value,
    ]);
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('owner can view his companies', function (): void {
    $user = User::factory()->create();
    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);

    $company = Company::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);
    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('owner.companies.index'));

    $response->assertOk();

    expect($company)->toHaveCount(3);

});
it('admin can view all companies', function (): void {
    $adminUser = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $adminUser->roles()->attach($adminRole->id);

    $adminCompany = Company::factory()->count(6)->create([
        'user_id' => $adminUser->id,
    ]);
    $ownerUser = User::factory()->create();
    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
    $ownerUser->roles()->attach($ownerRole->id);

    $ownerCompany = Company::factory()->count(6)->create([
        'user_id' => $ownerUser->id,
    ]);
    Sanctum::actingAs($adminUser, ['*']);

    $response = $this->getJson(route('admin.companies.index'));

    $response->assertOk();

    expect(Company::count())->toBe(12);

});
