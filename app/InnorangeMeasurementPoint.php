<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InnorangeMeasurementPoint extends Model
{
    protected $fillable = ['name', 'url', 'location'];
    protected $hidden = ['created_at', 'updated_at'];

    protected function Location()
    {
        return $this->hasOne(InnorangeLocation::class, 'id', 'location');
    }

    protected function Data()
    {
        return $this->hasMany(InnorangeData::class, 'measurement_point', 'id');
    }
}
