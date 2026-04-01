<?php

declare(strict_types=1);

namespace App\Services\Traits;

trait HasAiPrompt
{
    private function getPrompt(string $resumeText, string $jobDescription, string $configKey): string
    {
        $truncatedResume = $this->truncate($resumeText);
        $template = config($configKey);

        return str_replace(
            ['{resume}', '{job_description}'],
            [$truncatedResume, $jobDescription],
            $template
        );
    }

    private function truncate(string $text, int $maxLength = 16000): string
    {
        return mb_strlen($text) > $maxLength
            ? mb_substr($text, 0, $maxLength)
            : $text;
    }
}
