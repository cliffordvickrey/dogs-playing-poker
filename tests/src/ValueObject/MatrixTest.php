<?php

declare(strict_types=1);

namespace Test\Cliffordvickrey\DogsPlayingPoker\ValueObject;

use Cliffordvickrey\DogsPlayingPoker\ValueObject\Coordinates;
use Cliffordvickrey\DogsPlayingPoker\ValueObject\Matrix;
use PHPStan\Testing\TestCase;

class MatrixTest extends TestCase
{
    public function testClone(): void
    {
        $matrix = new Matrix(
            new Coordinates(0, 0),
            new Coordinates(0, 10),
            new Coordinates(10, 10),
            new Coordinates(10, 0)
        );

        $matrix2 = clone $matrix;
        $this->assertEquals($matrix->toCoordinatesArray(), $matrix2->toCoordinatesArray());
    }

    public function testToCoordinatesArray(): void
    {
        $matrix = new Matrix(
            new Coordinates(0, 0),
            new Coordinates(0, 10),
            new Coordinates(10, 10),
            new Coordinates(10, 0)
        );

        $array = $matrix->toCoordinatesArray();
        $this->assertEquals(0, $array[0]);
        $this->assertEquals(0, $array[1]);
        $this->assertEquals(0, $array[2]);
        $this->assertEquals(10, $array[3]);
        $this->assertEquals(10, $array[4]);
        $this->assertEquals(10, $array[5]);
        $this->assertEquals(10, $array[6]);
        $this->assertEquals(0, $array[7]);
    }

    public function testToCoordinatesArrayAssociative(): void
    {
        $matrix = new Matrix(
            new Coordinates(0, 0),
            new Coordinates(0, 10),
            new Coordinates(10, 10),
            new Coordinates(10, 0)
        );

        $array = $matrix->toCoordinatesArray(true);
        $this->assertEquals(['x' => 0, 'y' => 0], $array[0]);
        $this->assertEquals(['x' => 0, 'y' => 10], $array[1]);
        $this->assertEquals(['x' => 10, 'y' => 10], $array[2]);
        $this->assertEquals(['x' => 10, 'y' => 0], $array[3]);
    }
}
