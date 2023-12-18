<?php

namespace App\Models;

use App\Models\Crop;
use App\Traits\Uuids;
use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CropCategory extends Model
{
    use HasFactory, Uuids, DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    /**
     *Relationships
     *
     */

    /**
     * Get all of the crops for the CropCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function crops(): HasMany
    {
        return $this->hasMany(Crop::class, 'crop_category_id', 'id');
    }
}
