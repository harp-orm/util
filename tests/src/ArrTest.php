<?php

namespace CL\Util\Test;

use CL\Util\Arr;
use PHPUnit_Framework_TestCase;

class ArrTest extends PHPUnit_Framework_TestCase
{
    public function dataToAssoc()
    {
        return [
            [
                [],
                [],
            ],
            [
                ['test'],
                ['test' => null],
            ],
            [
                ['test' => []],
                ['test' => []],
            ],
            [
                ['test' => 'test2'],
                ['test' => ['test2' => null]],
            ],
            [
                ['test' => ['test2' => ['test3' => 'test4'], 'test5' => 'test6']],
                ['test' => ['test2' => ['test3' => ['test4' => null]], 'test5' => ['test6' => null]]],
            ],
        ];
    }

    /**
     * @covers CL\Util\Arr::toAssoc
     * @dataProvider dataToAssoc
     */
    public function testToAssoc($array, $expected)
    {
        $this->assertSame($expected, Arr::toAssoc($array));
    }

    public function dataInvoke()
    {
        return [
            [
                [],
                'test',
                [],
            ],
            [
                [new TestObject(), new TestObject()],
                'test',
                ['result', 'result'],
            ],
            [
                [new TestObject(), new TestObject()],
                'test2',
                ['result2', 'result2'],
            ],
        ];
    }

    /**
     * @covers CL\Util\Arr::invoke
     * @dataProvider dataInvoke
     */
    public function testInvoke($array, $methodName, $expected)
    {
        $this->assertSame($expected, Arr::invoke($array, $methodName));
    }

    public function dataPluckProperty()
    {
        return [
            [
                [],
                'test',
                [],
            ],
            [
                [new TestObject(), new TestObject()],
                'prop1',
                [1, 1],
            ],
            [
                [new TestObject(), new TestObject()],
                'prop2',
                [2, 2],
            ],
        ];
    }

    /**
     * @covers CL\Util\Arr::pluckProperty
     * @dataProvider dataPluckProperty
     */
    public function testPluckProperty($array, $property, $expected)
    {
        $this->assertSame($expected, Arr::pluckProperty($array, $property));
    }

    public function dataPluckUniqueProperty()
    {
        return [
            [
                [],
                'test',
                [],
            ],
            [
                [new TestObject(), new TestObject()],
                'prop1',
                [1],
            ],
            [
                [new TestObject(), new TestObject()],
                'prop2',
                [2],
            ],
        ];
    }

    /**
     * @covers CL\Util\Arr::pluckUniqueProperty
     * @dataProvider dataPluckUniqueProperty
     */
    public function testPluckUniqueProperty($array, $property, $expected)
    {
        $this->assertSame($expected, Arr::pluckUniqueProperty($array, $property));
    }

    public function dataPluck()
    {
        return [
            [
                [],
                'test',
                [],
            ],
            [
                [['atr' => 10, 'test' => 2], ['atr' => 10, 'test' => 5]],
                'test',
                [2, 5],
            ]
        ];
    }

    /**
     * @covers CL\Util\Arr::pluck
     * @dataProvider dataPluck
     */
    public function testPluck($array, $property, $expected)
    {
        $this->assertSame($expected, Arr::pluck($array, $property));
    }

    public function dataFlatten()
    {
        $obj = new TestObject();

        return [
            [
                [],
                [],
            ],
            [
                [['atr' => 10, 'test' => 2], [20]],
                ['atr' => 10, 'test' => 2, 20],
            ],
            [
                [['atr' => 10, 'test' => 2], [20]],
                ['atr' => 10, 'test' => 2, 20],
            ],
            [
                [['atr' => 10, $obj, 'test' => ['param' => 'test', 'test2' => [30, 50]]], [20, 'test' => ['test8' => 12]]],
                ['atr' => 10, $obj, 'param' => 'test', 30, 50, 20, 'test8' => 12],
            ]
        ];
    }

    /**
     * @covers CL\Util\Arr::flatten
     * @dataProvider dataFlatten
     */
    public function testFlatten($array, $expected)
    {
        $this->assertSame($expected, Arr::flatten($array));
    }

    public function dataGroupBy()
    {
        $callback = function ($item) {
            return substr($item, 0, 1);
        };

        return [
            [
                ['alpha', 'beta', 'gamma', 'getto', 'atton'],
                $callback,
                false,
                ['a' => ['alpha', 'atton'], 'b' => ['beta'], 'g' => ['gamma', 'getto']]
            ],
            [
                ['test1', 'test2', 'test3', '2222', '3333'],
                $callback,
                false,
                ['t' => ['test1', 'test2', 'test3'], '2' => ['2222'], '3' => ['3333']]
            ],
            [
                [3 => 'test1', 10 => 'test2', 20 => 'test3', 80 => '2222', 15 => '3333'],
                $callback,
                true,
                ['t' => [3 => 'test1', 10 => 'test2', 20 => 'test3'], '2' => [80 => '2222'], '3' => [15 => '3333']]
            ],
        ];
    }

    /**
     * @dataProvider dataGroupBy
     */
    public function testGroupBy($array, $callback, $preserveKeys, $expected)
    {
        $this->assertSame($expected, Arr::groupBy($array, $callback, $preserveKeys));
    }
}
