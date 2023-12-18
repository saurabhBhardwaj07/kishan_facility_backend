<?php

namespace App\Models;

use App\Models\Media;
use App\Traits\Uuids;
use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory, Uuids, DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'status',
    ];

    /**
     * Get the news's image.
     */
    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
