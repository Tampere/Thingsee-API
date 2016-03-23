<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

class PlacemeterController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    public function getMeasurementPoints()
    {
        $response = $this->client->request('GET', 'https://api.placemeter.net/api/v1/measurementpoints',
        [
            'headers' => [
                'Authorization' => 'Token ' . Config::get('placemeter.token')
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getMeasurementPoint($id)
    {
        $response = $this->client->request('GET', 'https://api.placemeter.net/api/v1/measurementpoints/'.$id,
            [
                'headers' => [
                    'Authorization' => 'Token ' . Config::get('placemeter.token')
                ]
            ]);

        return json_decode($response->getBody(), true);
    }

    public function getMeasurementPointData($id)
    {
        if(null == Input::get('start') || null == Input::get('end'))
        {
            return response()->json(['error' => 'Start and end are required'], 500);
        }

        $start = Input::get('start');
        $end = Input::get('end');
        $resolution = Input::get('resolution');
        $metrics = Input::get('metrics');

        try
        {
            $response = $this->client->request('GET', 'https://api.placemeter.net/api/v1/measurementpoints/' . $id . '/data?start=' . $start . '&end=' . $end,
                [
                    'headers' => [
                        'Authorization' => 'Token ' . Config::get('placemeter.token')
                    ]
                ]);
        } catch (RequestException $e)
        {
            return response()->json(['error' => $e->getResponse()->getReasonPhrase() . ' ' . $e->getResponse()->getStatusCode()], $e->getResponse()->getStatusCode());
        }
        return json_decode($response->getBody(), true);
    }
}
