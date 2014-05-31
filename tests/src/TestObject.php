<?php

namespace Harp\Util\Test;

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
