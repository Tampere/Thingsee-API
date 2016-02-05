<?php
/**
 * Created by PhpStorm.
 * User: antti
 * Date: 5.2.2016
 * Time: 10.55
 */

namespace Thingsee\Transformers;


class EventTransformer extends Transformer
{

    /**
     * Transform data to human readable vars, cast data types where necessary
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        return [
            'device' => [
                'id'   => (int) $item['device_id'],
                'self' => url('v1/envs/'.$item['device_id'])
            ],
            'sensor' => [
                'type' => $this->readableSensorName($item['sid']),
                'id'   => $item['sid'],
                'value'=> (double) $item['val']
            ],
            'timestamp' => (int) $item['ts'],
            'humantime' => $item['created_at'],
        ];
    }

    /**
     * Translate sensor id to human readable name
     * @param $sensor
     * @return string
     */
    private function readableSensorName($sensor)
    {
        $humanName = '';
        switch ($sensor)
        {
            case '0x00060100':
                $humanName = 'temperature';
                break;
            case '0x00060200':
                $humanName = 'humidity';
                break;
            case '0x00060300':
                $humanName = 'pressure';
                break;
            case '0x00060400':
                $humanName = 'luminance';
                break;
            default:
                $humanName = 'unknown sensor id';
                break;
        }

        return $humanName;
    }
}