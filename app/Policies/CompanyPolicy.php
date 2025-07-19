<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Roles;
use App\Models\Company;
use App\Models\User;

final class CompanyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->checkIfAdminOrOwner($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): bool
    {
        return $this->checkIfAdminOrOwner($user) && $this->checkIfUserOwnsThisCompany($user, $company);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Company $company): bool
    {
        return $this->checkIfAdminOrOwner($user) && $this->checkIfUserOwnsThisCompany($user, $company);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Company $company): bool
    {
        return $this->checkIfAdminOrOwner($user) && $this->checkIfUserOwnsThisCompany($user, $company);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Company $company): bool
    {
        return false;
    }

    private function checkIfAdminOrOwner(User $user): bool
    {
        return $user->hasRole(Roles::ADMIN) || $user->hasRole(Roles::OWNER);
    }

    private function checkIfUserOwnsThisCompany(User $user, Company $company): bool
    {
        return $company->user_id === $user->id;
    }
}
