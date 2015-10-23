<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{

	/**
	 * Device has many events
	 */
    public function event()
    {
    	return $this->hasMany('App\Event');
    }
}
