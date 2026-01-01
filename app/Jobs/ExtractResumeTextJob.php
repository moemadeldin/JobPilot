<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Resume;
use App\Services\ResumeTextExtractor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

final class ExtractResumeTextJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function __construct(private Resume $resume) {}

    public function handle(ResumeTextExtractor $extractor): void
    {
        $text = $extractor->extract($this->resume->path);

        if ($text) {
            $text = mb_convert_encoding($text, 'UTF-8', 'auto');
            $this->resume->update(['extracted_text' => $text]);
        }
    }
}
