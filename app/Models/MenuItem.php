<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use HasFactory;

    public const STATUSES = [
        'available',
        'out_of_stock',
        'archived',
    ];

    protected $fillable = [
        'name',
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
}

