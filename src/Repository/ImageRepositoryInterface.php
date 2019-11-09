<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker\Repository;

use Cliffordvickrey\DogsPlayingPoker\Image\ImageInterface;

/**
 * Interface for retrieving component images of the Dogs Playing Poker painting
 */
interface ImageRepositoryInterface
{
    /**
     * Fetches the destination image: a painting of Dogs Playing Poker
     * @return ImageInterface The image
     */
    public function getDogsPlayingPokerImage(): ImageInterface;

    /**
     * Fetches a card image to place over the painting of dogs playing poker
     * @param int $sourceId The ID of the card in the source image
     * @param int $destinationId The ID of the card in the destination image
     * @return ImageInterface The image
     */
    public function getCardImage(int $sourceId, int $destinationId): ImageInterface;
}
