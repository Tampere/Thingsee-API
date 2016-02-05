<?php
/**
 * Created by PhpStorm.
 * User: antti
 * Date: 5.2.2016
 * Time: 10.42
 */

namespace Thingsee\Transformers;


class DeviceTransformer extends Transformer
{
    public function transform($item)
    {
        return [
            'id'   => (int) $item['id'],
            'name' => $item['name'],
            'links' => [
                'self' => url('v1/envs/'.$item['id']),
                'data' => url('v1/envs/'.$item['id'].'/data')
            ]
        ];
    }
}