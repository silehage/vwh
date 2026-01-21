<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public static function permissions()
    {
        $userId = Auth::check() ? Auth::id() : null;
        
        if ($userId) {
            if($userId == 1) {
                return ['can-all'];
            }
            return Cache::remember('user_permissions_' . $userId, now()->addMinute(), function () use ($userId) {
                $data = DB::table('permissions')
                    ->join('permission_role', 'permission_role.permission_id', 'permissions.id')
                    ->join('roles', 'roles.id', 'permission_role.role_id')
                    ->join('users', 'users.role_id', 'roles.id')
                    ->where('users.id', $userId)
                    ->get()->pluck('name')->toArray();

                return $data;
            });
        }

        return [];
    }
}
