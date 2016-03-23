<?php

namespace App\Http\Controllers;

use App\InnorangeData;
use App\InnorangeMeasurementPoint;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InnorangeController extends Controller
{
    /**
     * Display a listing of the measurement points.
     *
     * @return Response
     */
    public function index()
    {
        $result = InnorangeMeasurementPoint::with('Location')->paginate(50);

        if(!$result)
        {
            return Response::json([
                'error' => [
                    'message' => 'No measurement points found'
                ]
            ], 400);
        }

        return $result;
    }

    /**
     * Returns an individual measurement point.
     * @param $id
     * @return mixed
     */
    public function getMeasurementPoint($id)
    {
        $result = InnorangeMeasurementPoint::find($id)
            ->with('Location')
            ->first();

        if(!$result)
        {
            return Response::json([
                'error' => [
                    'message' => 'No measurement point found with id ' . $id
                ]
            ], 400);
        }

        return $result;
    }

    /**
     * Returns the measurement data of an individual measurement point.
     * @param $id
     * @param Request $request
     * @return $this|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMeasurementPointData($id, Request $request)
    {

        $result = InnorangeData::with('MeasurementPoint')
            ->where('measurement_point', $id);

        if($request->has('sd')) {
            $result = $result->where('timestamp', '>=', $request->get('sd'));
        }

        if($request->has('ed')) {
            $result = $result->where('timestamp', '<=', $request->get('ed'));
        }

        $result = $result->paginate(50);


        if(!$result)
        {
            return Response::json([
                'error' => [
                    'message' => 'No measurement point found with id ' . $id
                ]
            ], 400);
        }

        return $result;
    }
}
