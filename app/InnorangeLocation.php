<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InnorangeLocation extends Model
{
    protected $fillable = ['latitude', 'longitude'];
    protected $hidden = ['id', 'created_at', 'updated_at'];
    protected $casts = ['latitude' => 'float', 'longitude' => 'float'];

    public function MeasurementPoint()
    {
        return $this->belongsTo(InnorangeMeasurementPoint::class, 'location', 'id');
    }
}
