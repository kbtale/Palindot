<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use app\Models\Url;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subset_name',
        'subset_descr',
        'subset_id',
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
     * Get the links of the subset
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(Url::class, 'subset_id');
    }
}
