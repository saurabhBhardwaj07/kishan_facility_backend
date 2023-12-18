<?php

namespace App\Models;

use App\Traits\Uuids;
use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductUser extends Model
{
    use HasFactory, Uuids, DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
    ];
}
