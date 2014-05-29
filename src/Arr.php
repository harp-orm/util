<?php

namespace CL\Util;

use Closure;

/**
 * @author     Ivan Kerin
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Arr
{
    /**
     * @param  array $array
     * @return array
     */
    public static function toAssoc(array $array)
    {
        $converted = array();

        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $converted[$value] = null;
            } else {
                $converted[$key] = self::toAssoc((array) $value);
            }
        }

        return $converted;
    }

    /**
     * @param  array  $arr
     * @param  string $method
     * @return array
     */
    public static function invoke(array $arr, $method)
    {
        return array_map(function ($item) use ($method) {
            return $item->$method();
        }, $arr);
    }

    /**
     * @param  array  $arr
     * @param  string $property
     * @return array
     */
    public static function pluckProperty(array $arr, $property)
    {
        return array_map(function ($item) use ($property) {
            return $item->$property;
        }, $arr);
    }

    /**
     * @param  array  $arr
     * @param  string $property
     * @return array
     */
    public static function pluckUniqueProperty(array $arr, $property)
    {
        return array_filter(
            array_unique(
                self::pluckProperty($arr, $property)
            )
        );
    }

    /**
     * @param  array  $array
     * @param  string $key
     * @return array
     */
    public static function pluck(array $array, $key)
    {
        return array_map(function ($item) use ($key) {
            return $item[$key];
        }, $array);
    }

    /**
     * @param  array $array
     * @return array
     */
    public static function flatten(array $array)
    {
        $result = array();

        array_walk_recursive($array, function ($value, $key) use (& $result) {
            if (is_numeric($key) or is_object($value)) {
                $result[] = $value;
            } else {
                $result[$key] = $value;
            }
        });

        return $result;
    }

    /**
     * @param  array                $array
     * @param  Closure|string|array $callback
     * @param  boolean              $preserveKeys
     * @return array
     */
    public static function groupBy($array, $callback, $preserveKeys = false)
    {
        $grouped = array();

        foreach ($array as $i => $item) {
            $itemGroup = call_user_func($callback, $item, $i);

            if (! isset($grouped[$itemGroup])) {
                $grouped[$itemGroup] = array();
            }

            if ($preserveKeys) {
                $grouped[$itemGroup][$i] = $item;
            } else {
                $grouped[$itemGroup] []= $item;
            }
        }

        return $grouped;
    }
}
