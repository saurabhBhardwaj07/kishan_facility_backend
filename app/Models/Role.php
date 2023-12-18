<?php

namespace App\Models;

use App\Traits\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasApiTokens, HasFactory, Notifiable, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * User Roles
     *
     */

    const ADMIN = 'admin';
    const USER = 'user';
}
