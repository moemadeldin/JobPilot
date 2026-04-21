<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class CreateCustomJobVacancyDTO
{
    public function __construct(
        public string $title,
        public ?string $job_category_id,
        public ?string $description,
        public ?string $location,
        public ?string $expected_salary,
        public string $employment_type,
        public string $status,
        public ?string $responsibilities,
        public ?string $requirements,
        public ?string $skills_required,
        public ?int $experience_years_min,
        public ?int $experience_years_max,
        public ?string $nice_to_have,
    ) {}

    public static function fromArray(array $data): self
    {
        assert(is_string($data['title']));

        return new self(
            title: $data['title'],
            job_category_id: isset($data['job_category_id']) && is_string($data['job_category_id']) ? $data['job_category_id'] : null,
            description: isset($data['description']) && is_string($data['description']) ? $data['description'] : null,
            location: isset($data['location']) && is_string($data['location']) ? $data['location'] : null,
            expected_salary: isset($data['expected_salary']) && is_string($data['expected_salary']) ? $data['expected_salary'] : null,
            employment_type: $data['employment_type'] ?? 'full-time',
            status: $data['status'] ?? 'active',
            responsibilities: isset($data['responsibilities']) && is_string($data['responsibilities']) ? $data['responsibilities'] : null,
            requirements: isset($data['requirements']) && is_string($data['requirements']) ? $data['requirements'] : null,
            skills_required: isset($data['skills_required']) && is_string($data['skills_required']) ? $data['skills_required'] : null,
            experience_years_min: isset($data['experience_years_min']) && is_int($data['experience_years_min']) ? $data['experience_years_min'] : null,
            experience_years_max: isset($data['experience_years_max']) && is_int($data['experience_years_max']) ? $data['experience_years_max'] : null,
            nice_to_have: isset($data['nice_to_have']) && is_string($data['nice_to_have']) ? $data['nice_to_have'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'job_category_id' => $this->job_category_id,
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
