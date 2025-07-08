<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

final class AuthException extends Exception
{
    public function __construct(
        string $message = 'Authentication error.',
        int $code = Response::HTTP_UNAUTHORIZED
    ) {
        parent::__construct($message, $code);
    }
}