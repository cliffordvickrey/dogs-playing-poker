<?php

declare(strict_types=1);

namespace Test\Cliffordvickrey\DogsPlayingPoker\ValueObject;

use Cliffordvickrey\DogsPlayingPoker\ValueObject\Coordinates;
use PHPUnit\Framework\TestCase;

class CoordinatesTest extends TestCase
{
    public function testGetX(): void
    {
        $coordinates = new Coordinates(100, 1000);
        $this->assertEquals(100, $coordinates->getX());
    }

    public function testGetY(): void
    {
        $coordinates = new Coordinates(100, 1000);
        $this->assertEquals(1000, $coordinates->getY());
    }
}