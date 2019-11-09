<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker\Math;

use Cliffordvickrey\DogsPlayingPoker\Exception\DogsPlayingPokerInvalidArgumentException;
use function count;
use function floor;
use function range;
use function sprintf;

/**
 * (@inheritDoc)
 */
class Permutation implements PermutationInterface
{
    /** @var int */
    private $number;
    /** @var int */
    private $numberChosen;
    /** @var int */
    private $permutationCount;

    /**
     * @param int $number Number of objects to combine
     * @param int $numberChosen Number of objects to order at any given time
     */
    public function __construct(int $number, int $numberChosen)
    {
        if ($numberChosen > $number) {
            throw new DogsPlayingPokerInvalidArgumentException('Number chosen cannot exceed number');
        }

        $this->number = $number;
        $this->numberChosen = $numberChosen;
        $this->permutationCount = self::getPermutations($this->number, $this->numberChosen);
    }

    /**
     * Applies the permutation formula:  P(n, r) = n!(n − r)!
     * @param int $number Number of objects to combine
     * @param int $numberChosen Number of objects to order at any given time
     * @return int The number of possible permutations
     */
    private static function getPermutations(int $number, int $numberChosen): int
    {
        return (int)floor(self::factorial($number) / self::factorial($number - $numberChosen));
    }

    /**
     * Returns a factorial (the product of a number and all integers below it)
     * @param int $number An integer
     * @return float The factorial
     */
    private static function factorial(int $number): float
    {
        $result = 1;
        for ($i = 0; $i < $number; $i++) {
            $result *= ($number - $i);
        }
        return (float)$result;
    }

    /**
     * (@inheritDoc)
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * (@inheritDoc)
     */
    public function getNumberChosen(): int
    {
        return $this->numberChosen;
    }

    /**
     * (@inheritDoc)
     */
    public function getPermutationById(int $permutationId): array
    {
        $this->assertValidPermutationId($permutationId);

        $digits = range(0, $this->number - 1);
        $index = $permutationId;
        $result = [];

        for ($i = 0; $i < $this->numberChosen; $i++) {
            $n = count($digits);
            $item = $index % $n;
            $index = floor($index / $n);
            $result[] = $digits[$item];
            array_splice($digits, $item, 1);
        }

        return $result;
    }

    /**
     * Throws an exception if a permutation ID is less than 1 or greater than P(n, r)
     * @param int $permutationId The permutation ID
     */
    private function assertValidPermutationId(int $permutationId): void
    {
        if ($permutationId < 1 || $permutationId > $this->permutationCount) {
            $message = sprintf(
                'Expected a $permutationId between 1 and %d; got %d',
                $this->permutationCount,
                $permutationId
            );
            throw new DogsPlayingPokerInvalidArgumentException($message);
        }
    }

    /**
     * (@inheritDoc)
     */
    public function getPermutationCount(): int
    {
        return $this->permutationCount;
    }
}
