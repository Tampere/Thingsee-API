<?php
/**
 * Created by PhpStorm.
 * User: antti
 * Date: 5.2.2016
 * Time: 10.40
 */

namespace Thingsee\Transformers;


abstract class Transformer
{
    /**
     * @param $devices
     * @return array
     */
    public function transformCollection($items)
    {
        return array_map([$this, 'transform'], $items);
    }

    public abstract function transform($item);
}