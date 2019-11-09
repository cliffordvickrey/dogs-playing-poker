<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker\Image;

use Cliffordvickrey\DogsPlayingPoker\ValueObject\Coordinates;
use Imagick;

/**
 * Interface for decorating an Imagick resource
 */
interface ImageInterface
{
    /**
     * Get the coordinates of the image (for overlaying on another image)
     * @return Coordinates The image coordinates
     */
    public function getCoordinates(): Coordinates;

    /**
     * Gets the Imagick resource
     * @return Imagick The Imagick resource
     */
    public function getResource(): Imagick;

    /**
     * Overlays another image onto the image instance
     * @param ImageInterface $image The image to place over the image
     */
    public function addOverlay(ImageInterface $image): void;
}
