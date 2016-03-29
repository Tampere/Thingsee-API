<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InnorangeMeasurementPoint extends Model
{
    protected $fillable = ['name', 'url', 'location'];
    protected $hidden = ['created_at', 'updated_at', 'location', 'url'];
    protected $appends = ['self_link', 'data_link'];
    protected $casts = ['id' => 'integer'];

    public function Location()
    {
        return $this->hasOne(InnorangeLocation::class, 'id', 'location');
    }

    public function Data()
    {
        return $this->hasMany(InnorangeData::class, 'measurement_point', 'id');
    }

    public function getSelfLinkAttribute()
    {
        return url('/v1/cellmeasurementpoints/'.$this->id);
    }

    public function getDataLinkAttribute()
    {
        return url('/v1/cellmeasurementpoints/'.$this->id.'/data');
    }
}
