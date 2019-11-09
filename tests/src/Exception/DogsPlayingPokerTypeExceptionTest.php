<?php

declare(strict_types=1);

namespace Test\Cliffordvickrey\DogsPlayingPoker\Exception;

use ArrayObject;
use Cliffordvickrey\DogsPlayingPoker\Exception\DogsPlayingPokerTypeException;
use PHPUnit\Framework\TestCase;
use stdClass;

class DogsPlayingPokerTypeExceptionTest extends TestCase
{
    public function testFromVariableScalar(): void
    {
        $blah = 'blah';
        $exception = DogsPlayingPokerTypeException::fromVariable('blah', 'int', $blah);
        $this->assertEquals('Variable blah has an unexpected type. Expected int; got string', $exception->getMessage());
    }

    public function testFromVariableObject(): void
    {
        $blah = new stdClass();
        $exception = DogsPlayingPokerTypeException::fromVariable('blah', ArrayObject::class, $blah);
        $this->assertEquals(
            'Variable blah has an unexpected type. Expected ArrayObject; got instance of stdClass',
            $exception->getMessage()
        );
    }
}