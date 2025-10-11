<?php

declare(strict_types=1);

namespace App\Interfaces;

interface ResumeTextExtractorInterface
{
    public function extract(string $path): ?string;
}
