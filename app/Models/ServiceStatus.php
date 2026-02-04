<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceStatus extends Model
{
    protected $fillable = [
        'name',
        'url',
        'status',
        'last_checked_at',
        'uptime_percentage',
        'response_time_ms',
        'notes',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
        'uptime_percentage' => 'decimal:2',
        'response_time_ms' => 'integer',
    ];

    public function isOperational(): bool
    {
        return $this->status === 'operational';
    }

    public function isDegraded(): bool
    {
        return $this->status === 'degraded';
    }

    public function isDown(): bool
    {
        return $this->status === 'down';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'operational' => 'success',
            'degraded' => 'warning',
            'down' => 'danger',
            default => 'gray',
        };
    }
}
