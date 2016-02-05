<?php

namespace App\Http\Controllers;

use App\Device;
use App\Event;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Log;
use Illuminate\Http\Request;
use App\Http\Requests;

use Thingsee\Transformers\DeviceTransformer;
use Thingsee\Transformers\EventTransformer;

class ThingseeController extends Controller
{
    /**
     * @var Thingsee\Transformers\DeviceTransformer
     */
    protected $deviceTransformer;

    /**
     * @var Thingsee\Transformers\EventTransformer
     */
    protected $eventTransformer;

    function __construct(DeviceTransformer $deviceTransformer, EventTransformer $eventTransformer)
    {
        $this->deviceTransformer = $deviceTransformer;
        $this->eventTransformer = $eventTransformer;
    }

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
        $device = isset($arguments['device']) ? $arguments['device'] : \Config::get('thingsee.defaultDevice'); 
        $sensor = isset($arguments['sensor']) ? $arguments['sensor'] : "";
        $limit = isset($arguments['limit']) ? $arguments['limit'] : \Config::get('thingsee.limit'); // Default limit

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

    /**
     * Get a list of devices
     * @return JSON Devices
     */
    public function getDevices()
    {
	    $devices =  Device::orderBy('updated_at', 'desc')->get(['id', 'name']);
	    if(!$devices)
	    {
		    return Response::json([
			    'error' => [
                    'message' => 'No devices found'
                    ]
		    ], 400);
	    }

	    return Response::json([
		    'data' => $this->deviceTransformer->transformCollection($devices->all())
	    ], 200);
    }

    /**
     * Get device info
     * @param $id
     * @return mixed
     */
    public function getDevice($id)
    {
        $device = Device::find($id);
        if(!$device)
        {
            return Response::json([
                'error' => [
                    'message' => 'Device does not exists'
                ]
            ], 404);
        }

        return Response::json([
            'data' => $this->deviceTransformer->transform($device->toArray())
        ], 200);
    }

    public function getDeviceData($id)
    {
        /**
         * Check query string parameters for dynamic scope modifications
         */
        $arguments = Input::all();

        $device = Device::find($id);

        if(!$device)
        {
            return Response::json([
                'error' => [
                    'message' => 'Device does not exists'
                ]
            ], 404);
        }

        // You can only query one device per request, 'cose multiple devices per request doesn't seem like a useful feature
        $sensor = isset($arguments['sensor']) ? $arguments['sensor'] : "";
        $limit = isset($arguments['limit']) ? ($arguments['limit'] > Config::get('thingsee.limit') ? Config::get('thingsee.limit') : $arguments['limit']) : Config::get('thingsee.limit'); // Default limit

        /**
         * Query for events, eager load device name, add scope modifiers based on uri params
         * @var Eloquent collection
         */
        //$events = Event::orderBy('updated_at', 'desc')->device($device['name'])->sensor($sensor)->subset($limit)->get(['sid', 'val', 'ts', 'created_at', 'device_id']);
        $events = Event::orderBy('updated_at', 'desc')->where('device_id', $id)->sensor($sensor)->paginate($limit);

        if(!$events)
        {
            return Response::json([
                'error' => [
                    'message' => 'No Event data found'
                ]
            ], 400);
        }

        $events->appends(Input::only('sensor', 'limit'));

        $total = $events->total();

        return Response::json([
            'data' => $this->eventTransformer->transformCollection($events->all()),
            'paginator' => [
                'total_count' => (int)$total,
                'total_pages' => ceil($total / $events->perPage()),
                'current_page'=> (int)$events->currentPage(),
                'limit'       => (int)$events->perPage(),
                'links' => [
                    'next' => $events->nextPageUrl(),
                    'prev' => $events->previousPageUrl()
                ]
            ]
        ], 200);
    }
}
