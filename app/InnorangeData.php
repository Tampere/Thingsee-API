<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InnorangeData extends Model
{
    protected $fillable = ['measurement_point', 'timestamp', 'visitors'];
    protected $hidden = ['id', 'created_at', 'updated_at', 'measurement_point'];

    public function MeasurementPoint()
    {
        return $this->belongsTo(InnorangeMeasurementPoint::class, 'measurement_point', 'id');
    }
}
