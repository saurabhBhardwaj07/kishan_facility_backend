<?php

namespace App\Models;

use App\Models\Media;
use App\Traits\Uuids;
use App\Traits\DateFormat;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Uuids, DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_category_id',
        'name',
        'quantity',
        'size',
        'price',
        'discount',
        'description',
        'composition',
        'uses',
        'status',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array<string, string>
     */
    protected $appends = [
        'discounted_price',
    ];

    public function getDiscountedPriceAttribute()
    {
        if ($this->price && $this->discount) {
            return number_format($this->price - ($this->price * $this->discount / 100), 2, '.', '');
        }
    }


    /**
     *Relationships
     *
     */

    /**
     * Get the product category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }

    /**
     * Get the product's image.
     */
    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
