<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Media\HasMedia;
use App\Media\Mediable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Modules\RolePermission\Traits\RolePermission;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable implements Mediable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, RolePermission, HasMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $guard_name = 'admin';
    protected $appends = ['image'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasPermission($permissionName)
    {
        $permissions = Cache::remember('user_permissions' . $this->id, now()->addMonths(5), function () {
            return $this->getAllPermissions()->pluck('name')->toArray();
        });
        return in_array($permissionName, $permissions);
    }

    public function setImageAttribute($file)
    {
        if ($file) {
            $this->deleteMedia();

            $this->addMedia($file, 'images', [
                'tags' => 'profile',
            ]);
        }
    }

    public function getImageAttribute()
    {
        return $this->getFirstUrl('images');
    }
}
