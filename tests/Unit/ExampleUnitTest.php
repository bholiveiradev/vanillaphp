<?php

namespace Tests\Unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use \stdClass;

class ExampleUnitTest extends TestCase
{
    public function testExample()
    {
        Mockery::mock(stdClass::class)->shouldReceive('test')->andReturn(true);
        $this->assertTrue(true);
    }
}
