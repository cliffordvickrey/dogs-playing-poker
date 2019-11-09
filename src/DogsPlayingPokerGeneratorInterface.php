<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker;

/**
 * The API for generating a Dogs Playing Poker instance
 */
interface DogsPlayingPokerGeneratorInterface
{
    /**
     * @param int|null $id The ID of the permutation painting, or NULL for a random painting
     * @return DogsPlayingPokerInterface An object encapsulating the painting
     */
    public function generate(?int $id = null): DogsPlayingPokerInterface;
}