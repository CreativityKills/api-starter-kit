<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Data\IssueAccessTokenDto;
use App\Data\IssuedAccessTokenDto;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Notifiable;
use App\Data\Enums\Abilities;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /** @use HasApiTokens<\Laravel\Sanctum\PersonalAccessToken> */
    use HasApiTokens;

    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }
}
