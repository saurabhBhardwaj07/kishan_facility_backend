<?php

namespace App\Models;

use App\Models\Media;
use App\Traits\Uuids;
use App\Traits\DateFormat;
use App\Models\CropCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Crop extends Model
{
    use HasFactory, Uuids, DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'crop_category_id',
        'name',
        'introduction',
        'climate',
        'soil',
        'season',
        'status',
    ];

    /**
     *Relationships
     *
     */

    /**
     * Get the crop category that owns the Crop
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cropCategory(): BelongsTo
    {
        return $this->belongsTo(CropCategory::class, 'crop_category_id', 'id');
    }

    /**
     * Get the Crop's image.
     */
    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
