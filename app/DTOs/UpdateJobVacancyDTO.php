<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class UpdateJobVacancyDTO
{
    public function __construct(
        public ?string $title,
        public ?string $job_category_id,
        public ?string $company_id,
        public ?string $description,
        public ?string $location,
        public ?string $expected_salary,
        public ?string $employment_type,
        public ?string $status,
        public ?string $responsibilities,
        public ?string $requirements,
        public ?string $skills_required,
        public ?int $experience_years_min,
        public ?int $experience_years_max,
        public ?string $nice_to_have,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ? (is_string($data['title']) ? $data['title'] : null) : null,
            job_category_id: $data['job_category_id'] ? (is_string($data['job_category_id']) ? $data['job_category_id'] : null) : null,
            company_id: $data['company_id'] ? (is_string($data['company_id']) ? $data['company_id'] : null) : null,
            description: $data['description'] ? (is_string($data['description']) ? $data['description'] : null) : null,
            location: $data['location'] ? (is_string($data['location']) ? $data['location'] : null) : null,
            expected_salary: $data['expected_salary'] ? (is_string($data['expected_salary']) ? $data['expected_salary'] : null) : null,
            employment_type: $data['employment_type'] ? (is_string($data['employment_type']) ? $data['employment_type'] : null) : null,
            status: $data['status'] ? (is_string($data['status']) ? $data['status'] : null) : null,
            responsibilities: $data['responsibilities'] ? (is_string($data['responsibilities']) ? $data['responsibilities'] : null) : null,
            requirements: $data['requirements'] ? (is_string($data['requirements']) ? $data['requirements'] : null) : null,
            skills_required: $data['skills_required'] ? (is_string($data['skills_required']) ? $data['skills_required'] : null) : null,
            experience_years_min: $data['experience_years_min'] ? (is_int($data['experience_years_min']) ? $data['experience_years_min'] : null) : null,
            experience_years_max: $data['experience_years_max'] ? (is_int($data['experience_years_max']) ? $data['experience_years_max'] : null) : null,
            nice_to_have: $data['nice_to_have'] ? (is_string($data['nice_to_have']) ? $data['nice_to_have'] : null) : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
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
