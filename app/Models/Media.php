<?php

namespace App\Models;

use App\Traits\Uuids;
use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory, Uuids, DateFormat;

    protected $fillable = ['type', 'title', 'notes', 'name', 'path', 'extension', 'mediable_type', 'mediable_id'];

    /**
     * Get the parent mediable model.
     */
    public function mediable()
    {
        return $this->morphTo();
    }
}
