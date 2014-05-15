<?php

namespace CL\Util\Test;

use CL\Util\Objects;
use SplObjectStorage;
use PHPUnit_Framework_TestCase;

class ObjectsTest extends PHPUnit_Framework_TestCase
{
    public function dataInvoke()
    {
        $objects = new SplObjectStorage();
        $objects->attach(new TestObject());
        $objects->attach(new TestObject());

        return [
            [
                new SplObjectStorage(),
                'test',
                [],
            ],
            [
                $objects,
                'test',
                ['result', 'result'],
            ],
            [
                $objects,
                'test2',
                ['result2', 'result2'],
            ],
        ];
    }

    /**
     * @covers CL\Util\Objects::invoke
     * @dataProvider dataInvoke
     */
    public function testInvoke($array, $methodName, $expected)
    {
        $this->assertSame($expected, Objects::invoke($array, $methodName));
    }

    public function dataCombineArrays()
    {
        $callback = function ($item1, $item2) {
            return $item1->id == $item2->id;
        };

        $obj1 = new TestObject(1);
        $obj2 = new TestObject(2);
        $obj3 = new TestObject(2);
        $obj4 = new TestObject(3);
        $obj5 = new TestObject(1);

        $result = new SplObjectStorage();
        $result[$obj1] = $obj5;
        $result[$obj2] = $obj3;

        return [
            [
                [],
                [],
                $callback,
                new SplObjectStorage(),
            ],
            [
                [$obj1, $obj2, $obj4],
                [$obj5, $obj3],
                $callback,
                $result,
            ],
        ];
    }

    /**
     * @covers CL\Util\Objects::combineArrays
     * @dataProvider dataCombineArrays
     */
    public function testCombineArrays($array1, $array2, $callback, $expected)
    {
        $this->assertEquals($expected, Objects::combineArrays($array1, $array2, $callback));
    }

    public function dataGroupCombineArrays()
    {
        $callback = function ($item1, $item2) {
            return $item1->id == $item2->id;
        };

        $obj1 = new TestObject(1);
        $obj2 = new TestObject(2);
        $obj3 = new TestObject(2);
        $obj4 = new TestObject(2);
        $obj5 = new TestObject(1);

        $result = new SplObjectStorage();
        $result[$obj1] = [$obj5];
        $result[$obj2] = [$obj3, $obj4];

        return [
            [
                [],
                [],
                $callback,
                new SplObjectStorage(),
            ],
            [
                [$obj1, $obj2],
                [$obj5, $obj3, $obj4],
                $callback,
                $result,
            ],
        ];
    }

    /**
     * @covers CL\Util\Objects::groupCombineArrays
     * @dataProvider dataGroupCombineArrays
     */
    public function testGroupCombineArrays($array1, $array2, $callback, $expected)
    {
        $this->assertEquals($expected, Objects::groupCombineArrays($array1, $array2, $callback));
    }

    /**
     * @covers CL\Util\Objects::addNested
     */
    public function testAddNested()
    {
        $objects = new SplObjectStorage();

        $obj1 = new TestObject();
        $obj2 = new TestObject();
        $obj3 = new TestObject();

        $result = Objects::addNested($objects, $obj1, $obj2);

        $expected = new SplObjectStorage();
        $expected[$obj1] = [$obj2];

        $this->assertEquals($expected, $result);

        $result = Objects::addNested($objects, $obj1, $obj3);

        $expected = new SplObjectStorage();
        $expected[$obj1] = [$obj2, $obj3];

        $this->assertEquals($expected, $result);
    }

    public function dataIndex()
    {
        $objects = new SplObjectStorage();
        $obj1 = new TestObject(1);
        $obj2 = new TestObject(2);
        $obj3 = new TestObject(3);

        $objects->attach($obj1);
        $objects->attach($obj2);
        $objects->attach($obj3);

        return [
            [
                new SplObjectStorage(),
                [],
            ],
            [
                $objects,
                [1 => $obj1, 2 => $obj2, 3 => $obj3],
            ],
        ];
    }

    /**
     * @covers CL\Util\Objects::index
     * @dataProvider dataIndex
     */
    public function testIndex($objects, $expected)
    {
        $this->assertSame($expected, Objects::index($objects, 'id'));
    }

    public function dataFilter()
    {
        $callback = function ($item) {
            return $item->id == 1;
        };

        $objects = new SplObjectStorage();
        $obj1 = new TestObject(1);
        $obj2 = new TestObject(2);
        $obj3 = new TestObject(1);

        $objects->attach($obj1);
        $objects->attach($obj2);
        $objects->attach($obj3);

        $result = new SplObjectStorage();
        $result->attach($obj1);
        $result->attach($obj3);

        return [
            [
                new SplObjectStorage(),
                $callback,
                new SplObjectStorage(),
            ],
            [
                $objects,
                $callback,
                $result,
            ],
        ];
    }

    /**
     * @covers CL\Util\Objects::filter
     * @dataProvider dataFilter
     */
    public function testFilter($objects, $callback, $expected)
    {
        $this->assertEquals($expected, Objects::filter($objects, $callback));
    }

    /**
     * @covers CL\Util\Objects::toArray
     */
    public function testToArray()
    {
        $objects = new SplObjectStorage();
        $obj1 = new TestObject(1);
        $obj2 = new TestObject(2);
        $obj3 = new TestObject(1);

        $objects->attach($obj1);
        $objects->attach($obj2);
        $objects->attach($obj3);

        $expected = [$obj1, $obj2, $obj3];

        $this->assertEquals($expected, Objects::toArray($objects));
    }

    public function dataGroupBy()
    {
        return [
            [
                ['alpha', 'beta', 'gamma', 'getto', 'atton'],
                $callback,
                ['a' => ['alpha', 'atton'], 'b' => ['beta'], 'g' => ['gamma', 'getto']]
            ],
            [
                ['test1', 'test2', 'test3', '2222', '3333'],
                $callback,
                ['t' => ['test1', 'test2', 'test3'], '2' => ['2222'], '3' => ['3333']]
            ],
        ];
    }

    /**
     * @covers CL\Util\Objects::groupBy
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
