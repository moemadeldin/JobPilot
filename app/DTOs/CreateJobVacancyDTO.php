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
        public string $is_active,
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
            is_active: $data['is_active'],
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
            'is_active' => $this->is_active,
        ];
    }
}
