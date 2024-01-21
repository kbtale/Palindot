<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UrlSubset;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Subset;

class Url extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'base_url',
        'to_url',
    ];

    /**
     * Function to generate the shortened Urls
     * 
     * 
     */
    public static function generateUrl(string $baseUrl, $length = 17): String {
        $baseUrlChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-._~:/?#[]@!$&\'()*+,;=';
        $hash = substr(hash('sha256', $baseUrl), 0, $length);
        $halfLength = floor($length / 2);
        $firstHalf = '';
    
        for ($i = 0; $i < $halfLength; $i++) {
            $firstHalf .= $baseUrlChars[ord($hash[$i]) % strlen($baseUrlChars)];
        }
    
        $secondHalf = strrev(substr($firstHalf, 0, $length % 2 == 0 ? $halfLength : $halfLength - 1));
    
        return app()->make('url')->to($firstHalf . $secondHalf);
    }

    /**
     * Get the subsets of the link
     *
     * @return BelongsToMany
     */
    public function subsets(): BelongsToMany
    {
        return $this->belongsToMany(Subset::class, 'link_subset');
    }
}
