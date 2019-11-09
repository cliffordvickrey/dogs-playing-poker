<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker;

/**
 * Dogs Playing Poker painting result
 */
interface DogsPlayingPokerInterface
{
    /**
     * Saves the painting to a resource
     * @param resource $resource The resource to save to
     */
    public function writeDogsToResource($resource): void;

    /**
     * Gets the generating painting as a blob string
     * @return string The blob
     */
    public function getDogsAsBlob(): string;

    /**
     * Gets the permutation ID used to generate the painting
     * @return int The permutation ID
     */
    public function getPermutationId(): int;

    /**
     * Returns the list of cards (as IDs) visible in the painting
     * @return int[] A list of card IDs
     */
    public function getCardIds(): array;
}