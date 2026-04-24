<?php

declare(strict_types=1);

namespace App\Services;

final readonly class ParseJobVacancyService
{
    private const string SYSTEM_PROMPT =
        'You are a job vacancy parser. Extract only relevant job information from the text. '.
        'Return only valid JSON with null for fields that cannot be determined. '.
        'Ignore any unrelated content, ads, navigation, or garbage text. '.
        'Be strict - only extract actual job details.';

    public function __construct(private GroqClient $client) {}

    public function parse(string $jobText): array
    {
        $prompt = $this->buildPrompt($jobText);

        /** @var array<mixed, mixed> $data */
        $data = $this->client->requestJson(self::SYSTEM_PROMPT, $prompt);

        return [
            'title' => $this->stringOrNull($data, 'title'),
            'company' => $this->stringOrNull($data, 'company'),
            'description' => $this->stringOrNull($data, 'description'),
            'location' => $this->stringOrNull($data, 'location'),
            'employment_type' => $this->normalizeEmploymentType($this->stringOrNull($data, 'employment_type')),
            'responsibilities' => $this->stringOrNull($data, 'responsibilities'),
            'requirements' => $this->stringOrNull($data, 'requirements'),
            'skills_required' => $this->stringOrNull($data, 'skills_required'),
            'experience_years_min' => $this->intOrNull($data, 'experience_years_min'),
            'experience_years_max' => $this->intOrNull($data, 'experience_years_max'),
            'expected_salary' => $this->normalizeSalary($this->stringOrNull($data, 'expected_salary')),
            'category' => $this->stringOrNull($data, 'category'),
        ];
    }

    private function buildPrompt(string $jobText): string
    {
        $rules = <<<'RULES_WRAP'
        CRITICAL RULES:
        - employment_type: MUST be exactly "full-time" or "part-time". If uncertain or any other value, use null.
        - expected_salary: Only return numeric values like "50000" or "80000". If salary is "Confidential", "DOE", "Negotiable", "N/A", or any non-numeric text, use null.
        RULES_WRAP;

        $format = <<<'FORMAT'
Return JSON in this exact format:
{
    "title": "Job title",
    "company": "Company name",
    "description": "Job description summary",
    "location": "City, State or Remote",
    "employment_type": "full-time or part-time only",
    "responsibilities": "Main job responsibilities",
    "requirements": "Key requirements",
    "skills_required": "Required skills",
    "experience_years_min": null or number,
    "experience_years_max": null or number,
    "expected_salary": "numeric value only, null if not explicitly a number",
    "category": "Job category if obvious"
}
FORMAT;

        return "Parse the following job vacancy text and extract the structured information.\n"
            ."If a field cannot be determined from the text, use null.\n\n"
            ."Job Text:\n".$jobText."\n\n"
            .$rules."\n\n"
            .$format;
    }

    private function stringOrNull(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }

    private function intOrNull(array $data, string $key): ?int
    {
        $value = $data[$key] ?? null;

        return is_int($value) && $value >= 0 ? $value : null;
    }

    private function normalizeEmploymentType(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = mb_strtolower(mb_trim($value));

        return match (true) {
            str_contains($normalized, 'full') => 'full-time',
            str_contains($normalized, 'part') => 'part-time',
            default => null,
        };
    }

    private function normalizeSalary(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $cleaned = str_replace(['$', ','], '', mb_trim($value));

        return is_numeric($cleaned) ? $cleaned : null;
    }
}
