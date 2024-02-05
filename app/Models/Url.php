<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Subset;
use EloquentFilter\Filterable;

/**
 * @OA\Schema(
 *     schema="Url",
 *     type="object",
 *     description="This is the URL model class",
 *     @OA\Property(
 *         property="base_url",
 *         type="string",
 *         description="The base URL"
 *     ),
 *     @OA\Property(
 *         property="to_url",
 *         type="string",
 *         description="The shortened URL"
 *     ),
 *     @OA\Property(
 *         property="subset_id",
 *         type="integer",
 *         description="The ID of the subset to which the URL belongs"
 *     )
 * )
 */
class Url extends Model
{
    use HasFactory, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'base_url',
        'to_url',
        'subset_id'
    ];

    /**
     * @OA\Schema(
     *     definition="generateUrl",
     *     type="function",
     *     description="Function to generate the shortened Urls",
     * )
     * @OA\Response(
     *     response=200,
     *     description="Generated shortened URL",
     *     @OA\Schema(
     *         type="string"
     *     )
     * )
     */
    public static function generateUrl(string $baseUrl, $length = 15): String {
        $baseUrlChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-._~:/?#[]@!$&\'()*+,;=';

        $hash = substr(hash('sha256', $baseUrl . microtime()), 0, $length);
        $halfLength = floor($length / 2);
        $firstHalf = '';
    
        for ($i = 0; $i < $halfLength; $i++) {
            $firstHalf .= $baseUrlChars[ord($hash[$i]) % strlen($baseUrlChars)];
        }
    
        $secondHalf = strrev(substr($firstHalf, 0, $length % 2 == 0 ? $halfLength : $halfLength - 1));
    
        return app()->make('url')->to($firstHalf . $secondHalf);
    }

    /**
     * Get the subset of the link
     *
     * @return BelongsTo
     */
    public function subset(): BelongsTo
    {
        return $this->belongsTo(Subset::class);
    }
}
