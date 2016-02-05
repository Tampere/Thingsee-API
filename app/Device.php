<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{

    protected $hidden = ['deviceAuthUuid', 'created_at', 'updated_at'];

	/**
	 * Device has many events
	 */
    public function event()
    {
    	return $this->hasMany('App\Event');
    }
}
