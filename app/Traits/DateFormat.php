<?php

namespace App\Traits;

trait DateFormat
{
    /**
     * Modifying created at and updated at date
     *
     * @param [date] $value
     * @return void
     */
    public function getCreatedAtAttribute($value)
    {
        return date("d-m-Y h:i:a", strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date("d-m-Y h:i:a", strtotime($value));
    }
}
