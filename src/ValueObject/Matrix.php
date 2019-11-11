<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker\ValueObject;

use function array_merge;
use function array_reduce;

/**
 * Encapsulates coordinates for a polygonal matrix. Use self::@toCoordinatesArray to retrieve the coordinates in the
 * array format that a particular Imagick method expects
 */
final class Matrix
{
    private $points;

    public function __construct(Coordinates...$points)
    {
        $this->points = $points;
    }

    public function __clone()
    {
        foreach ($this->points as $i => $point) {
            $this->points[$i] = clone $point;
        }
    }

    /**
     * @param bool $associative Whether to return a list of associative arrays ['x' => x, 'y' => y]
     * @return array The array of coordinates
     */
    public function toCoordinatesArray(bool $associative = false): array
    {
        return array_reduce(
            $this->points,
            function (array $carry, Coordinates $point) use ($associative): array {
                if ($associative) {
                    return array_merge($carry, [['x' => $point->getX(), 'y' => $point->getY()]]);
                }

                return array_merge($carry, [$point->getX(), $point->getY()]);
            },
            []
        );
    }

}
