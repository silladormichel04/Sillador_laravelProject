<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUSES = [
        'available',
        'out_of_stock',
        'archived',
    ];

    protected $fillable = [
        'name',
        'photo',
        'price',
        'status',
        'description',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * A menu item optionally belongs to a category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Accessor for a public URL to the stored photo.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        return asset('storage/'.$this->photo);
    }

    /**
     * Accessor for initials used when no photo is available.
     */
    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->map(fn (string $part) => Str::substr($part, 0, 1))
            ->take(2)
            ->implode('');
    }
}

