<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ThingseeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEvents()
    {
        /**
         * Check query string parameters for dynamic scope modifications
         */
        $arguments = \Input::all();

        // You can only query one device per request, 'cose multiple devices per request doesn't seem like a useful feature
        $device = isset($arguments['device']) ? $arguments['device'] : ""; 
        $sensor = isset($arguments['sensor']) ? $arguments['sensor'] : "";
        $limit = isset($arguments['limit']) ? $arguments['limit'] : 50; // Default limit - move to conf file

        /**
         * Query for events, eager load device name, add scope modifiers based on uri params
         * @var Eloquent collection
         */
        $events = \App\Event::orderBy('updated_at', 'desc')->with(array('device' => function($query) 
            {
                $query->addSelect(array('id', 'name'));
            }))->device($device)->sensor($sensor)->subset($limit)->get(['sid', 'val', 'ts', 'created_at', 'device_id']);

        return $events;   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEvents(Request $request)
    {
        // Get the device identifier
        $deviceauthuuid = $request->header('deviceauthuuid');

        // Find the correct device
        $device = \App\Device::where('deviceAuthUuid', $deviceauthuuid)->firstOrFail();

        if($request->has('0.senses')) {

            // Get the sensor data
            $senses = $request->input("0.senses");

            // Iterate sensor data and update as neccessary
            foreach ($senses as $sense) {
                $clauses = ['device_id' => $device->id, 'sid' => $sense['sId']];
                $currentSense = \App\Event::where($clauses)->orderBy('ts')->first();                
                if(!$currentSense || $currentSense->ts < $sense['ts']) {
                    $newSense = \App\Event::create(['sid' => $sense['sId'], 'val' => $sense['val'], 'ts' => $sense['ts'], 'device_id' => $device->id]);
                    Log::info('Added a new event');
                } else {
                    Log::info('No need to update event data');
                }
            }
        } else {
            Log::info('Request didn\'t contain event info');
        }
    }
}
