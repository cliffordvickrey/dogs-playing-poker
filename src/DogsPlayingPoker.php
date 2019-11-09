<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker;

use Imagick;

class DogsPlayingPoker implements DogsPlayingPokerInterface
{
    private $im;
    private $permutationId;
    private $cardIds;

    public function __construct(Imagick $im, int $permutationId, array $cardIds)
    {
        $this->im = $im;
        $this->permutationId = $permutationId;
        $this->cardIds = $cardIds;
    }

    /**
     * (@inheritDoc)
     */
    public function getDogsAsBlob(): string
    {
        return $this->im->getImageBlob();
    }

    /**
     * (@inheritDoc)
     */
    public function writeDogsToResource($resource): void
    {
        $this->im->writeImageFile($resource);
    }

    /**
     * (@inheritDoc)
     */
    public function getCardIds(): array
    {
        return $this->cardIds;
    }

    /**
     * (@inheritDoc)
     */
    public function getPermutationId(): int
    {
        return $this->permutationId;
    }
}