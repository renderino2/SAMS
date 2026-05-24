<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'location',
        'contact_person',
        'contact_email',
        'contact_phone',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users that belong to this office.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'office_id');
    }

    /**
     * Get the office heads that belong to this office.
     */
    public function officeHeads()
    {
        return $this->hasMany(User::class, 'office_id')->where('role', 'Office Head');
    }
}
