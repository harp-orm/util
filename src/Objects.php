<?php

namespace Harp\Util;

use SplObjectStorage;
use Closure;

/**
 * @author     Ivan Kerin <ikerin@gmail.com>
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
class Objects
{
    /**
     * @param  SplObjectStorage $storage
     * @param  string           $method
     * @return array
     */
    public static function invoke(SplObjectStorage $storage, $method)
    {
        $mapped = array();

        foreach ($storage as $object) {
            $mapped []= $object->$method();
        }

        return $mapped;
    }

    /**
     * @param  SplObjectStorage $storage
     * @param  Closure          $closure
     * @return array
     */
    public static function map(SplObjectStorage $storage, Closure $closure)
    {
        $result = array();

        foreach ($storage as $object) {
            $result []= $closure($object, $storage->getInfo());
        }

        return $result;
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
     * @param  Closure          $filter
     * @return SplObjectStorage
     */
    public static function sort(SplObjectStorage $storage, Closure $filter)
    {
        $array = iterator_to_array($storage);

        usort($array, $filter);

        return Objects::fromArray($array);
    }

    /**
     * @param  SplObjectStorage $storage
     * @return array
     */
    public static function toArray(SplObjectStorage $storage)
    {
        return iterator_to_array($storage);
    }

    /**
     * @param  array            $array
     * @return SplObjectStorage
     */
    public static function fromArray(array $array)
    {
        $objects = new SplObjectStorage();

        foreach ($array as $object) {
            $objects->attach($object);
        }

        return $objects;
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
