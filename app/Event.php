<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	/**
	 * quarded columns blacklisted against mass-assign
	 * @var array quarded columns
	 */
    protected $quarded = ['id'];

    /**
     * All events belong to a device
     */
    public function device()
    {
    	return $this->belongsTo('App\Device');
    }
}
