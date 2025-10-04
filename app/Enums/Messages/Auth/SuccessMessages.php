<?php

declare(strict_types=1);

namespace App\Enums\Messages\Auth;

enum SuccessMessages: string
{
    case REGISTERED = 'User Registered Successfully.';
    case LOGGED_IN = 'User Logged in Successfully.';
    case JOB_CATEGORY_CREATED = 'Job Category Created Successfully.';
    case JOB_CATEGORY_UPDATED = 'Job Category Updated Successfully.';
    case COMPANY_CREATED = 'Company Created Successfully.';
    case COMPANY_UPDATED = 'Company Updated Successfully.';
    case JOB_VACANCY_CREATED = 'Job Vacancy Created Successfully.';
    case JOB_VACANCY_UPDATED = 'Job Vacancy Updated Successfully.';
    case FILTERED_SUCCESS = 'Filtered Successfully';
}
