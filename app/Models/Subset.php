<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use app\Models\UrlSubset;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Url;

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
     * @return BelongsToMany
     */
    public function urls(): BelongsToMany
    {
        return $this->belongsToMany(Url::class, 'link_subset');
    }
}
