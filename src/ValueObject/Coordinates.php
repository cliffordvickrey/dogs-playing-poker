<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker\ValueObject;

/**
 * Encapsulates an X and Y pair. Couldn't be simpler!
 */
final class Coordinates
{
    private $x;
    private $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}
