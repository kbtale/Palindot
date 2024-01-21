<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Subset;
use App\Models\Url;

class UrlSubset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'link_id',
        'subset_id',
    ];

    /**
     * Retrieves the subset of the pair
     *
     * @return     BelongsTo  The belongs to.
     */
    public function subset(): BelongsTo
    {
        return $this->belongsTo(Subset::class);
    }

    /**
     * Retrieves the link of the pair
     *
     * @return     BelongsTo  The belongs to.
     */
    public function url(): BelongsTo
    {
        return $this->belongsTo(Url::class);
    }
}
