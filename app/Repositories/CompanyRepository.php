<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class CompanyRepository
{
    private const NUMBER_OF_COMPANIES = 3;

    public function getAllCompaniesByOwner(User $user, int $number_of_companies = self::NUMBER_OF_COMPANIES): LengthAwarePaginator
    {
        return Company::with('owner')
            ->where('user_id', $user->id)
            ->paginate($number_of_companies);
    }

    public function getAllCompanies(int $number_of_companies = self::NUMBER_OF_COMPANIES): LengthAwarePaginator
    {
        return Company::with('owner')->paginate($number_of_companies);
    }
}
