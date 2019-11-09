<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker\Math;

/**
 * Interface for generating permutations of Dogs Playing Poker paintings by ID
 */
interface PermutationInterface
{
    /**
     * @return int The number of objects to combine (e.g., cards in a deck) (n)
     */
    public function getNumber(): int;

    /**
     * @return int The number of objects that can be ordered at any given time (r)
     */
    public function getNumberChosen(): int;

    /**
     * @param int $permutationId The permutation ID
     * @return int[] An array of object IDs
     */
    public function getPermutationById(int $permutationId): array;

    /**
     * @return int The number of possible permutations [P(n, r)]
     */
    public function getPermutationCount(): int;
}