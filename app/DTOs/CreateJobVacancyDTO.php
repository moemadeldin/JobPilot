<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class CreateJobVacancyDTO
{
    public function __construct(
        public string $title,
        public string $job_category_id,
        public string $company_id,
        public string $description,
        public string $location,
        public string $expected_salary,
        public string $employment_type,
        public string $status,
        public string $responsibilities,
        public string $requirements,
        public string $skills_required,
        public int $experience_years_min,
        public int $experience_years_max,
        public ?string $nice_to_have,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            job_category_id: $data['job_category_id'],
            company_id: $data['company_id'],
            description: $data['description'],
            location: $data['location'],
            expected_salary: $data['expected_salary'],
            employment_type: $data['employment_type'],
            status: $data['status'],
            responsibilities: $data['responsibilities'],
            requirements: $data['requirements'],
            skills_required: $data['skills_required'],
            experience_years_min: $data['experience_years_min'],
            experience_years_max: $data['experience_years_max'],
            nice_to_have: $data['nice_to_have'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'job_category_id' => $this->job_category_id,
            'company_id' => $this->company_id,
            'description' => $this->description,
            'location' => $this->location,
            'expected_salary' => $this->expected_salary,
            'employment_type' => $this->employment_type,
            'status' => $this->status,
            'responsibilities' => $this->responsibilities,
            'requirements' => $this->requirements,
            'skills_required' => $this->skills_required,
            'experience_years_min' => $this->experience_years_min,
            'experience_years_max' => $this->experience_years_max,
            'nice_to_have' => $this->nice_to_have,
        ];
    }
}
