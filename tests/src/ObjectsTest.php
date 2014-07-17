<?php

namespace Harp\Util\Test;

use Harp\Util\Objects;
use SplObjectStorage;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass Harp\Util\Objects
 *
 * @author     Ivan Kerin <ikerin@gmail.com>
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
class ObjectsTest extends PHPUnit_Framework_TestCase
{
    public function dataMap()
    {
        $objects = new SplObjectStorage();
        $objects->attach(new TestObject(1));
        $objects->attach(new TestObject(3));

        $objects2 = new SplObjectStorage();
        $objects2->attach(new TestObject(1), new TestObject(2));
        $objects2->attach(new TestObject(3), new TestObject(4));

        return array(
            array(
                new SplObjectStorage(),
                function ($obj, $info) {
                    return $obj->id.'-'.$info->id;
                },
                array(),
            ),
            array(
                $objects,
                function ($obj) {
                    return $obj->id;
                },
                array(1, 3),
            ),
            array(
                $objects2,
                function ($obj, $info) {
                    return $obj->id.'-'.$info->id;
                },
                array('1-2', '3-4'),
            )
        );
    }

    /**
     * @covers ::map
     * @dataProvider dataMap
     */
    public function testMap($array, $closure, $expected)
    {
        $this->assertSame($expected, Objects::map($array, $closure));
    }

    public function dataInvoke()
    {
        $objects = Objects::fromArray(array(new TestObject(), new TestObject()));

        return array(
            array(
                new SplObjectStorage(),
                'test',
                array(),
            ),
            array(
                $objects,
                'test',
                array('result', 'result'),
            ),
            array(
                $objects,
                'test2',
                array('result2', 'result2'),
            ),
        );
    }

    /**
     * @covers ::invoke
     * @dataProvider dataInvoke
     */
    public function testInvoke($array, $methodName, $expected)
    {
        $this->assertSame($expected, Objects::invoke($array, $methodName));
    }

    public function dataFilter()
    {
        $callback = function ($item) {
            return $item->id == 1;
        };

        $obj1 = new TestObject(1);
        $obj2 = new TestObject(2);
        $obj3 = new TestObject(1);

        return array(
            array(
                new SplObjectStorage(),
                $callback,
                new SplObjectStorage(),
            ),
            array(
                Objects::fromArray(array($obj1, $obj2, $obj3)),
                $callback,
                Objects::fromArray(array($obj1, $obj3)),
            ),
        );
    }

    /**
     * @covers ::filter
     * @dataProvider dataFilter
     */
    public function testFilter($objects, $callback, $expected)
    {
        $this->assertEquals($expected, Objects::filter($objects, $callback));
    }

    /**
     * @covers ::Sort
     */
    public function testSort()
    {
        $obj1 = new TestObject(5);
        $obj2 = new TestObject(10);
        $obj3 = new TestObject(32);

        $objects = Objects::fromArray(array($obj1, $obj3, $obj2));

        $sorted = Objects::sort($objects, function ($item1, $item2) {
            return $item1->id - $item2->id;
        });

        $sortedArray = iterator_to_array($sorted);

        $this->assertSame(array($obj1, $obj2, $obj3), $sortedArray);
    }

    /**
     * @covers ::fromArray
     */
    public function testFromArray()
    {
        $obj1 = new TestObject(1);
        $obj2 = new TestObject(2);
        $obj3 = new TestObject(1);

        $expected = new SplObjectStorage();
        $expected->attach($obj1);
        $expected->attach($obj2);
        $expected->attach($obj3);

        $array = array($obj1, $obj2, $obj3);

        $this->assertEquals($expected, Objects::fromArray($array));
    }

    /**
     * @covers ::toArray
     */
    public function testToArray()
    {
        $obj1 = new TestObject(1);
        $obj2 = new TestObject(2);
        $obj3 = new TestObject(1);

        $objects = Objects::fromArray(array($obj1, $obj2, $obj3));

        $expected = array($obj1, $obj2, $obj3);

        $this->assertEquals($expected, Objects::toArray($objects));
    }

    public function dataGroupBy()
    {
        return array(
            array(
                array('alpha', 'beta', 'gamma', 'getto', 'atton'),
                $callback,
                array('a' => array('alpha', 'atton'), 'b' => array('beta'), 'g' => array('gamma', 'getto'))
            ),
            array(
                array('test1', 'test2', 'test3', '2222', '3333'),
                $callback,
                array('t' => array('test1', 'test2', 'test3'), '2' => array('2222'), '3' => array('3333'))
            ),
        );
    }

    /**
     * @covers ::groupBy
     */
    public function testGroupBy()
    {
        $callback = function ($item) {
            return $item->id;
        };

        $group1 = new TestObject(1);
        $group2 = new TestObject(2);

        $obj1 = new TestObject($group1);
        $obj2 = new TestObject($group1);
        $obj3 = new TestObject($group2);

        $objects = new SplObjectStorage();
        $objects->attach($obj1);
        $objects->attach($obj2);
        $objects->attach($obj3);

        $expected = new SplObjectStorage();

        $expected[$group1] = new SplObjectStorage();
        $expected[$group1]->attach($obj1);
        $expected[$group1]->attach($obj2);

        $expected[$group2] = new SplObjectStorage();
        $expected[$group2]->attach($obj3);

        $this->assertEquals($expected, Objects::groupBy($objects, $callback));
    }
}
