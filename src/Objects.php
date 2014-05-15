<?php

namespace CL\Util;

use SplObjectStorage;
use Closure;

/*
 * @author     Ivan Kerin
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Objects
{
    /**
     * @param  array   $arr
     * @param  array   $arr2
     * @param  Closure $yield
     * @return SplObjectStorage
     */
    public static function combineArrays(array $arr, array $arr2, Closure $yield)
    {
        $items = new SplObjectStorage();

        foreach ($arr as $item) {
            foreach ($arr2 as $item2) {
                if ($yield($item, $item2)) {
                    $items->attach($item, $item2);
                }
            }
        }

        return $items;
    }

    /**
     * @param  array   $arr
     * @param  array   $arr2
     * @param  Closure $yield
     * @return SplObjectStorage
     */
    public static function groupCombineArrays(array $arr, array $arr2, Closure $yield)
    {
        $groups = new SplObjectStorage();

        foreach ($arr as $item) {
            foreach ($arr2 as $item2) {
                if ($yield($item, $item2)) {
                    self::addNested($groups, $item, $item2);
                }
            }
        }

        return $groups;
    }

    /**
     * @param SplObjectStorage $storage
     * @param mixed           $parent
     * @param mixed           $child
     * @return SplObjectStorage
     */
    public static function addNested(SplObjectStorage $storage, $parent, $child)
    {
        $current = $storage->contains($parent)
            ? $storage[$parent]
            : array();

        array_push($current, $child);

        $storage[$parent] = $current;

        return $storage;
    }

    /**
     * @param  SplObjectStorage $storage
     * @param  string           $property
     * @return arrau
     */
    public static function index(SplObjectStorage $storage, $property)
    {
        $result = [];
        foreach ($storage as $item) {
            $result[$item->{$property}] = $item;
        }
        return $result;
    }

    /**
     * @param  SplObjectStorage $storage
     * @param  string           $method
     * @return array
     */
    public static function invoke(SplObjectStorage $storage, $method)
    {
        $mapped = [];

        foreach ($storage as $object) {
            $mapped []= $object->$method();
        }

        return $mapped;
    }

    /**
     * @param  SplObjectStorage $storage
     * @param  Closure          $filter
     * @return SplObjectStorage
     */
    public static function filter(SplObjectStorage $storage, Closure $filter)
    {
        $filtered = new SplObjectStorage();

        foreach ($storage as $object) {
            if ($filter($object)) {
                $filtered->attach($object);
            }
        }

        return $filtered;
    }

    /**
     * @param  SplObjectStorage $storage
     * @return array
     */
    public static function toArray(SplObjectStorage $storage)
    {
        $items = [];

        foreach ($storage as $item) {
            $items []= $item;
        }

        return $items;
    }

    /**
     * @param  SplObjectStorage $storage
     * @param  Closure          $callback
     * @return SplObjectStorage
     */
    public static function groupBy(SplObjectStorage $storage, Closure $callback)
    {
        $groups = new SplObjectStorage();

        foreach ($storage as $item) {
            $key = $callback($item);

            if ($groups->contains($key)) {
                $groups[$key]->attach($item);
            } else {
                $group = new SplObjectStorage();
                $group->attach($item);
                $groups->attach($key, $group);
            }
        }

        return $groups;
    }
}
