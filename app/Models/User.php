<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'student_id_number',
        'full_name',
        'email',
        'password',
        'password_hash',
        'role',
        'office',
        'contact',
        'gender',
        'active',
        'status',
        'profile_photo',
        'address',
        'guardian_address',
        'course',
        'year_level',
        'section',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'password_hash',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that appends to returned entities.
     *
     * @var array
     */
    protected $appends = ['photo'];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash ?? $this->password;
    }

    /**
     * Get the user's photo attribute.
     *
     * @return string
     */
    public function getPhotoAttribute()
    {
        // Return a default placeholder image or user's actual photo if available
        return $this->attributes['photo'] ?? asset('build/assets/images/placeholders/200x200.jpg');
    }

    /**
     * The getter that return accessible URL for user photo.
     *
     * @var array
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo !== null) {
            return url('media/user/' . $this->id . '/' . $this->photo);
        } else {
            return asset('build/assets/images/placeholders/200x200.jpg');
        }
    }
}
