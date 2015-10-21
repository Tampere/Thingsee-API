<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Config;

class ThingseeController extends Controller
{
	/**
	 * @param  Illuminate\Http\Request $request
	 * @param  string $device
	 * @return json events
	 */
    public function getEvents(Request $request, $device)
    {
    	/**
    	 * Limit events
    	 * @var integer
    	 */
    	$limit = 10;

    	/**
    	 * Start at timestamp
    	 * @var integer
    	 */
    	$start = 0;

    	/**
    	 * End at string
    	 * @var string
    	 */
    	$end = "";

    	// Set query params based on request params
    	if($request->has('limit')) $limit = $request->get('limit');
    	if($request->has('start')) $start = $request->get('start');
    	if($request->has('end')) $end = "&end=" . $request->get('end');

    	try {
			$thingsee = new \Thingsee\ThingseeAPI();		
    	} catch (\GuzzleHttp\Exception\ClientException $e) {
    		dd($e);
    	}

    	// Return events in json
		return $thingsee->getEvents($device, "?senses=0x00060100,0x00060200,0x00060400,0x00060300&limit=" . $limit . "&start=" . $start . $end);
    }

    /**
     * @return json List of devices
     */
    public function getDevices() 
    {
    	$devices = Array(
    		'ThingseeKaupungintalo' => Config::get('thingsee.device1'), 
    		'ThingseeNasinneula' => Config::get('thingsee.device2')
    	);

    	return $devices;
    }
}
