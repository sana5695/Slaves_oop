<?php

abstract class Factory
{
    static function create($data, $type)
    {
        $objectData = array();
        foreach ($data as $item) {
            $object = new $type(...$item);
            $objectData[$item[0]] = $object;
        }

        return $objectData;
    }
}
