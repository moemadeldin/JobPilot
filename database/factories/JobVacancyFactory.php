<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EmploymentType;
use App\Enums\Status;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use App\Models\User;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
final class JobVacancyFactory extends Factory
{
    use RefreshOnCreate;

    protected $model = JobVacancy::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $minExp = $this->faker->numberBetween(1, 2);

        return [
            'title' => $this->faker->title(),
            'job_category_id' => JobCategory::factory(),
            'company_id' => Company::factory(),
            'description' => $this->faker->text(200),
            'location' => $this->faker->city(),
            'expected_salary' => $this->faker->numberBetween(30000, 120000),
            'employment_type' => EmploymentType::FULL_TIME->value,
            'status' => Status::ACTIVE->value,
            'responsibilities' => $this->faker->text(200),
            'requirements' => $this->faker->text(200),
            'skills_required' => $this->faker->text(200),
            'experience_years_min' => $minExp,
            'experience_years_max' => $this->faker->numberBetween($minExp, 10),
            'nice_to_have' => $this->faker->text(100),
        ];
    }
}
