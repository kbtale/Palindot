<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Subset;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     description="This is the User model class",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the user"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="The email of the user"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         description="The password of the user"
 *     )
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * User avatar url
     *
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar
        ? Storage::disk('public')->url($this->avatar)
        : 'gravatar';
    }

    /**
     * User default avatar
     *
     * @return string
     */
    public function getGravatar(): string
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?s=80&d=retro';
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the subsets of the user
     *
     * @return HasMany
     */
    public function subsets(): HasMany
    {
        return $this->hasMany(Subset::class, 'user_id');
    }
}
