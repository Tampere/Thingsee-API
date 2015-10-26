<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	protected $fillable = ['sid', 'val', 'ts', 'device_id'];

    /**
     * All events belong to a device
     */
    public function device()
    {
    	return $this->belongsTo('App\Device');
    }

    /**
     * Limit query scope to one device
     * @param  Illuminate\Database\Eloquent $query  Query object to modify
     * @param  string $device DeviceAuthUuid
     * @return Illuminate\Database\Eloquent         modified query object
     */
    public function scopeDevice($query, $device)
    {
        if(strlen($device) < 1) return;
        $dev = \App\Device::where('deviceAuthUuid', $device)->firstOrFail();
        return $query->where('device_id', $dev->id);
    }

    /**
     * Limit query scope to one sensor
     * @param  Illuminate\Database\Eloquent $query  Query object to modify
     * @param  string $sensor sId
     * @return Illuminate\Database\Eloquent         modified query object
     */
    public function scopeSensor($query, $sensor)
    {
        if(strlen($sensor) < 1) return;
        return $query->where('sid', $sensor);
    }

    public function scopeSubset($query, $limit)
    {
        if(strlen($limit) < 1) return;
        return $query->limit($limit);
    }
}
