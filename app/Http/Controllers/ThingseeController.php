<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get the device identifier
        $deviceauthuuid = $request->header('deviceauthuuid');

        if($request->has('0.senses')) {

            // Get the sensor data
            $senses = $request->input("0.senses");

            // print_r($request->all());

            // Iterate sensor data and update as neccessary
            foreach ($senses as $sense) {
                // $sense['sId']
                // $sense['val']
                // $sense['ts'];
                // 
                // 1. Tarkistetaan viimeisimmän kyseisen laitteen kyseisen anturin tallennettu arvo
                // 2. Tallennetaan uusi arvo, jos ts > tallennettu ts
            }
        } else {
            echo "Invalid post";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
