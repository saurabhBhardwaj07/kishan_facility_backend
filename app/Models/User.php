<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Media;
use App\Traits\Uuids;
use App\Models\UserOrder;
use App\Traits\DateFormat;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Uuids, DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'email',
        'image',
        'device_token',
        'google_id',
        'email_verified_at',
        'password',
        'status',
        'remember_token',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array<string, string>
     */
    protected $appends = [
        'name',
    ];

    public function getNameAttribute()
    {
        if ($this->middle_name) {
            return $this->first_name . " " . $this->middle_name . " " . $this->last_name;
        }
        return $this->first_name . " " . $this->last_name;
    }

    /**
     *Relationships
     *
     */

    /**
     * Get the role that belongs the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Get all of the addresses for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    /**
     * Get default addresses for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function defaultAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'id')->where('is_default', 1);
    }

    /**
     * Get all of the products for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(UserProduct::class, 'user_id', 'id');
    }

    /**
     * Get all of the orders for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(UserOrder::class, 'user_id', 'id');
    }

    /**
     * Get the user's image.
     */
    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

}
