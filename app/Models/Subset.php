<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Url;
use EloquentFilter\Filterable;

class Subset extends Model
{
    use HasFactory, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subset_name',
        'subset_descr',
        'user_id',
    ];

    /**
     * Retrieves the owner of the subset
     *
     * @return     BelongsTo  The belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the urls of the subset
     *
     * @return HasMany
     */
    public function urls(): HasMany
    {
        return $this->hasMany(Url::class, 'subset_id');
    }
}
