<?php

declare(strict_types=1);

namespace App\Traits;

trait HasAiPrompt
{
    protected function getPrompt(string $resumeText, string $jobDescription, string $configKey): string
    {
        /** @var string $template */
        $template = config($configKey);

        $truncatedResume = $this->truncate($resumeText);

        return str_replace(
            ['{resume}', '{job_description}'],
            [$truncatedResume, $jobDescription],
            $template
        );
    }

    protected function truncate(string $text, int $maxLength = 16000): string
    {
        return mb_strlen($text) > $maxLength
            ? mb_substr($text, 0, $maxLength)
            : $text;
    }
}
