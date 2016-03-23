<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InnorangeLocation extends Model
{
    protected $fillable = ['latitude', 'longitude'];
    protected $hidden = ['id', 'created_at', 'updated_at'];

    protected function MeasurementPoint()
    {
        return $this->belongsTo(InnorangeMeasurementPoint::class, 'location', 'id');
    }
}
