<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'published_year',
        'isbn',
        'issn',
        'category',
        'classification',
        'location',
        'availability_status',
        'cover_image',
        'synopsis',
        'curator_notes',
        'stock_total',
        'stock_available',
        'is_public',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'published_year' => 'integer',
        'is_public' => 'boolean',
        'stock_total' => 'integer',
        'stock_available' => 'integer',
    ];

    // ── Accessors ─────────────────────────────────────
    public function getFormattedIdAttribute(): string
    {
        return 'BK-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getCoverUrlAttribute(): string
    {
        if (!$this->cover_image) {
            return asset('images/book-placeholder.svg');
        }

        if (str_starts_with($this->cover_image, 'images_covers/')) {
            return asset($this->cover_image);
        }

        return asset('storage/' . $this->cover_image);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->availability_status) {
            'tersedia' => ['text' => 'text-ink', 'bg' => 'bg-surface', 'label' => 'Tersedia'],
            'dipinjam' => ['text' => 'text-coffee', 'bg' => 'bg-surface', 'label' => 'Dipinjam'],
            'reservasi' => ['text' => 'text-muted', 'bg' => 'bg-surface', 'label' => 'Reservasi'],
            'arsip' => ['text' => 'text-muted', 'bg' => 'bg-surface', 'label' => 'Arsip'],
            'perbaikan' => ['text' => 'text-muted', 'bg' => 'bg-surface', 'label' => 'Perbaikan'],
            default => ['text' => 'text-muted', 'bg' => 'bg-surface', 'label' => $this->availability_status],
        };
    }

    // ── Scopes ───────────────────────────────────────
    public function scopeTersedia($query)
    {
        return $query->where('availability_status', 'tersedia')
            ->where('stock_available', '>', 0);
    }

    public function scopePublik($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('author', 'like', "%{$keyword}%")
                ->orWhere('isbn', 'like', "%{$keyword}%")
                ->orWhere('publisher', 'like', "%{$keyword}%");
        });
    }

    // ── Relationships ───────────────────────────────
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
