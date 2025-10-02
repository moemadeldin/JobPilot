<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

final readonly class CompanyPolicy
{
    public function create(User $user): bool
    {
        return $this->checkIfAdminOrOwner($user);
    }

    public function update(User $user, Company $company): bool
    {
        return $this->checkIfAdminOrOwner($user) || $this->checkIfUserOwnsThisCompany($user, $company);
    }

    public function delete(User $user, Company $company): bool
    {
        return $this->checkIfAdminOrOwner($user) || $this->checkIfUserOwnsThisCompany($user, $company);
    }

    public function restore(User $user, Company $company): bool
    {
        return $this->checkIfAdminOrOwner($user) || $this->checkIfUserOwnsThisCompany($user, $company);
    }

    private function checkIfAdminOrOwner(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    private function checkIfUserOwnsThisCompany(User $user, Company $company): bool
    {
        return $company->user_id === $user->id;
    }
}
