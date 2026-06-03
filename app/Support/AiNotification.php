<?php

namespace App\Support;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AiNotification
{
    public static function sendFailure(Throwable|string $error, string $context = 'AI request'): void
    {
        $message = $error instanceof Throwable ? $error->getMessage() : $error;
        $message = self::safeMessage($message);

        Notification::make()
            ->title($context.' Failed')
            ->body($message)
            ->danger()
            ->persistent()
            ->send();

        Log::error($context.' failed: '.$message, [
            'exception' => $error instanceof Throwable ? $error::class : null,
            'trace' => $error instanceof Throwable ? $error->getTraceAsString() : null,
        ]);
    }

    public static function safeMessage(string $message): string
    {
        $message = preg_replace('/Bearer\s+[A-Za-z0-9._\-]+/i', 'Bearer [REDACTED]', $message) ?? $message;
        $message = preg_replace('/api[_-]?key[\"\'\s:=]+[A-Za-z0-9._\-]+/i', 'api_key=[REDACTED]', $message) ?? $message;

        return Str::limit($message, 450);
    }
}
