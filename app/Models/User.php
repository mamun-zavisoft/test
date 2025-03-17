<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Media\HasMedia;
use App\Media\Mediable;
use App\Modules\RolePermission\Traits\RolePermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable implements Mediable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasMedia, Notifiable, RolePermission;

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
        'role',
        'zone_id',
    ];

    public static $SUPER_ADMIN = 1; // Full Access
    public static $IN_CHARGE = 2; // in-charge: who are in-charge (zone head)
    public static $FOREMAN = 3; // foreman or users who are under control of in-charge
    
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
        $permissions = Cache::remember('user_permissions'.$this->id, now()->addMonths(5), function () {
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
