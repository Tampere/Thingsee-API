<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Config;

class ThingseeController extends Controller
{
    public function getEvents(Request $request, $device)
    {
    	$limit = 10;
    	$start = 0;
    	$end = "";

    	if($request->has('limit')) $limit = $request->get('limit');
    	if($request->has('start')) $start = $request->get('start');
    	if($request->has('end')) $end = "&end=" . $request->get('end');

    	try {
			$thingsee = new \Thingsee\ThingseeAPI();		
    	} catch (\GuzzleHttp\Exception\ClientException $e) {
    		dd($e);
    	}

		return $thingsee->getEvents($device, "?senses=0x00060100,0x00060200,0x00060400,0x00060300&limit=" . $limit . "&start=" . $start . $end);
    }

    public function getDevices() 
    {
    	$devices = Array(
    		'ThingseeKaupungintalo' => Config::get('thingsee.device1'), 
    		'ThingseeNasinneula' => Config::get('thingsee.device2')
    	);

    	return $devices;
    }
}
