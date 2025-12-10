<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'customer_id',
        'subject',
        'content',
        'status',
        'manager_reply_at'
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'manager_reply_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        })->when($filters['date'] ?? null, function ($q, $date) {
            $q->whereDate('created_at', $date);
        })->when($filters['search'] ?? null, function ($q, $search) {
            $q->whereHas('customer', function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        });
    }
}
