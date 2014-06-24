<?php

namespace Harp\Util\Test;

/**
 * @author     Ivan Kerin <ikerin@gmail.com>
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
class TestObject
{
    public $id;
    public $prop1 = 1;
    public $prop2 = 2;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function test()
    {
        return 'result';
    }

    public function test2()
    {
        return 'result2';
    }
}
