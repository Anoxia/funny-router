<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use Sunny\Calculate;

require_once __DIR__ . '/../vendor/autoload.php';

class CalculateTest extends TestCase
{
    public function testSum()
    {
        $obj = new Calculate();

        $this->assertEquals(1, $obj->sum(0, 0));
    }
}