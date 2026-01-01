<?php

declare(strict_types=1);

namespace App\Enums\Messages\Auth;

enum SuccessMessages: string
{
    case REGISTERED = 'User Registered Successfully.';
    case LOGGED_IN = 'User Logged in Successfully.';
    case CODE_SENT = 'Verification Code Sent Successfully.';
    case CODE_IS_CORRECT = 'Verification Code is Correct.';
    case PASSWORD_RECOVERED = 'Password has been recovered.';
    case JOB_CATEGORY_CREATED = 'Job Category Created Successfully.';
    case JOB_CATEGORY_UPDATED = 'Job Category Updated Successfully.';
    case COMPANY_CREATED = 'Company Created Successfully.';
    case COMPANY_UPDATED = 'Company Updated Successfully.';
    case JOB_VACANCY_CREATED = 'Job Vacancy Created Successfully.';
    case JOB_VACANCY_UPDATED = 'Job Vacancy Updated Successfully.';
    case JOB_VACANCY_RETRIEVED = 'Job Vacancy has retrieved successfully.';
    case FILTERED_SUCCESS = 'Filtered Successfully';
    case RESUME_UPLOADED = 'Resume has been uploaded.';
    case APPLICATION_SUBMITTED = 'Application submitted successfully.';
    case MOCK_INTERVIEW_EXPECTED_QUESTIONS = 'Expected Interview Questions.';
}
