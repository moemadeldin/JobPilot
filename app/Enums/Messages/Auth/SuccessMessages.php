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
    case JOB_VACANCY_CREATED = 'Job Vacancy Created Successfully.';
    case FILTERED_SUCCESS = 'Filtered Successfully';
    case RESUME_UPLOADED = 'Resume has been uploaded.';
    case APPLICATION_SUBMITTED = 'Application submitted successfully.';
    case MOCK_INTERVIEW_EXPECTED_QUESTIONS = 'Expected Interview Questions.';
}
