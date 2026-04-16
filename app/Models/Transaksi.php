<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'book_id',
        'booking_code',
        'pickup_deadline',
        'borrowed_date',
        'due_date',
        'returned_date',
        'status',
        'notes',
        'rejection_reason',
        'verified_by',
        'verified_at',
        'fine_amount',
        'fine_paid',
    ];

    protected $casts = [
        'borrowed_date' => 'date',
        'due_date' => 'date',
        'returned_date' => 'date',
        'pickup_deadline' => 'datetime',
        'verified_at' => 'datetime',
        'fine_paid' => 'boolean',
    ];

    // ── Boot: Auto-generate booking code ─────────────────
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            if (empty($transaksi->booking_code)) {
                $transaksi->booking_code = 'SCR-' . strtoupper(Str::random(8));
            }
            if (empty($transaksi->pickup_deadline) && $transaksi->status === 'pending') {
                $transaksi->pickup_deadline = now()->addHours(24);
            }
        });
    }

    public function getFormattedIdAttribute(): string
    {
        return 'TXN-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending' => ['text' => 'text-coffee', 'bg' => 'bg-surface', 'label' => 'Menunggu'],
            'dipinjam' => ['text' => 'text-ink', 'bg' => 'bg-ink/5', 'label' => 'Dipinjam'],
            'dikembalikan' => ['text' => 'text-coffee', 'bg' => 'bg-surface', 'label' => 'Dikembalikan'],
            'terlambat' => ['text' => 'text-red-700', 'bg' => 'bg-surface', 'label' => 'Terlambat'],
            'ditolak' => ['text' => 'text-muted', 'bg' => 'bg-surface', 'label' => 'Ditolak'],
            'expired' => ['text' => 'text-muted', 'bg' => 'bg-surface', 'label' => 'Hangus'],
            default => ['text' => 'text-muted', 'bg' => 'bg-surface', 'label' => $this->status],
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'dipinjam' && $this->due_date && now()->gt($this->due_date);
    }

    // ── Scopes ───────────────────────────────────────
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'dipinjam');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'dipinjam')
            ->where('due_date', '<', now()->toDateString());
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('booking_code', 'like', "%{$keyword}%")
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$keyword}%"))
                ->orWhereHas('book', fn($q) => $q->where('title', 'like', "%{$keyword}%"));
        });
    }

    // ── Relationships ───────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approve(User $admin): bool
    {
        if (!$admin->isAdmin() && !$admin->isPetugas()) {
            return false;
        }

        return DB::transaction(function () use ($admin) {
            // Lock user & book untuk atomic operation
            $user = User::lockForUpdate()->find($this->user_id);
            $book = Book::lockForUpdate()->find($this->book_id);

            if ($this->status !== 'pending' || $book->stock_available < 1) {
                return false;
            }

            // Check limit with lock
            $activeLoans = $user->transaksis()->active()->count();
            if ($activeLoans >= 4) return false;

            $this->update([
                'status' => 'dipinjam',
                'borrowed_date' => now()->toDateString(),
                'due_date' => now()->addDays(7)->toDateString(),
                'verified_by' => $admin->id,
                'verified_at' => now(),
            ]);

            $book->decrement('stock_available');
            return true;
        });
    }

    public function reject(User $admin, string $reason): bool
    {
        if ($this->status !== 'pending') return false;

        $this->update([
            'status' => 'ditolak',
            'rejection_reason' => $reason,
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        return true;
    }

    public function returnBook(): bool
    {
        if ($this->status !== 'dipinjam' && $this->status !== 'terlambat') return false;

        if ($this->is_overdue) {
            $daysLate = $this->due_date->diffInDays(now());
            $this->fine_amount = $daysLate * 1000; // Rp 1.000/hari (sesuaikan kebijakan)
        }

        $this->update([
            'status' => 'dikembalikan',
            'returned_date' => now()->toDateString(),
        ]);

        $this->book->increment('stock_available');

        return true;
    }

    public function markOverdue(): bool
    {
        if ($this->status === 'dipinjam' && $this->is_overdue) {
            return $this->update(['status' => 'terlambat']);
        }
        return false;
    }

    public function expire(): bool
    {
        if ($this->status === 'pending' && $this->pickup_deadline && now()->gt($this->pickup_deadline)) {
            return $this->update(['status' => 'expired']);
        }
        return false;
    }
}
