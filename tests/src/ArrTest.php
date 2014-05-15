<?php

namespace CL\Util\Test;

use CL\Util\Arr;
use PHPUnit_Framework_TestCase;

class ArrTest extends PHPUnit_Framework_TestCase
{
    public function dataToAssoc()
    {
        return array(
            array(
                array(),
                array(),
            ),
            array(
                array('test'),
                array('test' => null),
            ),
            array(
                array('test' => array()),
                array('test' => array()),
            ),
            array(
                array('test' => 'test2'),
                array('test' => array('test2' => null)),
            ),
            array(
                array('test' => array('test2' => array('test3' => 'test4'), 'test5' => 'test6')),
                array('test' => array('test2' => array('test3' => array('test4' => null)), 'test5' => array('test6' => null))),
            ),
        );
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
        return array(
            array(
                array(),
                'test',
                array(),
            ),
            array(
                array(new TestObject(), new TestObject()),
                'test',
                array('result', 'result'),
            ),
            array(
                array(new TestObject(), new TestObject()),
                'test2',
                array('result2', 'result2'),
            ),
        );
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
        return array(
            array(
                array(),
                'test',
                array(),
            ),
            array(
                array(new TestObject(), new TestObject()),
                'prop1',
                array(1, 1),
            ),
            array(
                array(new TestObject(), new TestObject()),
                'prop2',
                array(2, 2),
            ),
        );
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
        return array(
            array(
                array(),
                'test',
                array(),
            ),
            array(
                array(new TestObject(), new TestObject()),
                'prop1',
                array(1),
            ),
            array(
                array(new TestObject(), new TestObject()),
                'prop2',
                array(2),
            ),
        );
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
        return array(
            array(
                array(),
                'test',
                array(),
            ),
            array(
                array(array('atr' => 10, 'test' => 2), array('atr' => 10, 'test' => 5)),
                'test',
                array(2, 5),
            )
        );
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

        return array(
            array(
                array(),
                array(),
            ),
            array(
                array(array('atr' => 10, 'test' => 2), array(20)),
                array('atr' => 10, 'test' => 2, 20),
            ),
            array(
                array(array('atr' => 10, 'test' => 2), array(20)),
                array('atr' => 10, 'test' => 2, 20),
            ),
            array(
                array(array('atr' => 10, $obj, 'test' => array('param' => 'test', 'test2' => array(30, 50))), array(20, 'test' => array('test8' => 12))),
                array('atr' => 10, $obj, 'param' => 'test', 30, 50, 20, 'test8' => 12),
            )
        );
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

        return array(
            array(
                array('alpha', 'beta', 'gamma', 'getto', 'atton'),
                $callback,
                false,
                array('a' => array('alpha', 'atton'), 'b' => array('beta'), 'g' => array('gamma', 'getto'))
            ),
            array(
                array('test1', 'test2', 'test3', '2222', '3333'),
                $callback,
                false,
                array('t' => array('test1', 'test2', 'test3'), '2' => array('2222'), '3' => array('3333'))
            ),
            array(
                array(3 => 'test1', 10 => 'test2', 20 => 'test3', 80 => '2222', 15 => '3333'),
                $callback,
                true,
                array('t' => array(3 => 'test1', 10 => 'test2', 20 => 'test3'), '2' => array(80 => '2222'), '3' => array(15 => '3333'))
            ),
        );
    }

    /**
     * @dataProvider dataGroupBy
     */
    public function testGroupBy($array, $callback, $preserveKeys, $expected)
    {
        $this->assertSame($expected, Arr::groupBy($array, $callback, $preserveKeys));
    }
}
