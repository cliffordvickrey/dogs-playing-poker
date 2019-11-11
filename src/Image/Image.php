<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker\Image;

use Cliffordvickrey\DogsPlayingPoker\ValueObject\Coordinates;
use Cliffordvickrey\DogsPlayingPoker\ValueObject\Matrix;
use Imagick;
use ImagickDraw;
use ImagickException;
use ImagickPixel;
use function floor;

/**
 * Decorates an Imagick object. Transforms the image per the parameters passed in the constructor
 */
final class Image implements ImageInterface
{
    /** @var Imagick */
    private $im;
    /** @var Coordinates */
    private $coordinates;

    /**
     * @param Imagick $im The Imagick resource
     * @param Coordinates|null $coordinates Coordinates of the image in the Dogs Playing Poker painting
     * @param float|null $scale Scale with which to resize the image
     * @param bool $flip Whether or not to flip the image
     * @param float|null $rotation Degrees by which to rotate the image
     * @param Matrix|null $controlPoints Control points with which to apply a perspective distortion
     * @param Matrix|null $mask Area of the image to make transparent
     * @throws ImagickException
     */
    public function __construct(
        Imagick $im,
        ?Coordinates $coordinates = null,
        ?float $scale = null,
        bool $flip = false,
        ?float $rotation = null,
        ?Matrix $controlPoints = null,
        ?Matrix $mask = null
    )
    {
        $im->setImageFormat('png');

        if (null !== $mask) {
            $im = self::applyMask($im, $mask);
        }

        if (null !== $controlPoints) {
            $im = self::applyPerspectiveDistortion($im, $controlPoints);
        }

        if ($flip) {
            $im->flipImage();
            $im->flopImage();
        }

        if (null !== $rotation && 0.0 !== $rotation) {
            $im = self::rotateImage($im, $rotation);
        }

        if (null !== $scale && 1.0 !== $scale) {
            $im = self::resizeImage($im, $scale);
        }

        $this->coordinates = $coordinates ?? new Coordinates(0, 0);
        $this->im = $im;
    }

    /**
     * Add transparency to the image
     * @param Imagick $im The Imagick resource
     * @param Matrix $matrix The polygonal shape of the mask
     * @return Imagick The Imagick resource
     * @throws ImagickException
     */
    private static function applyMask(Imagick $im, Matrix $matrix): Imagick
    {
        $mask = new Imagick();
        $mask->newImage($im->getImageWidth(), $im->getImageHeight(), new ImagickPixel('none'));
        $mask->setImageFormat('png');

        $array = $matrix->toCoordinatesArray(true);
        $polygon = new ImagickDraw();
        $polygon->setFillColor(new ImagickPixel('black'));
        $polygon->polygon($array);
        $mask->drawImage($polygon);

        $im = self::ensureTransparency($im);
        $im->compositeImage($mask, Imagick::COMPOSITE_DSTOUT, 0, 0);
        return $im;
    }

    /**
     * Ensure that an image is in matte mode, and that new visual areas added to the image are transparent
     * @param Imagick $im Imagick resource
     * @return Imagick The Imagick resource
     */
    private static function ensureTransparency(Imagick $im): Imagick
    {
        $im->setImageMatte(true);

        if (Imagick::VIRTUALPIXELMETHOD_TRANSPARENT !== $im->getImageVirtualPixelMethod()) {
            $im->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
        }

        return $im;
    }

    /**
     * Applies a perspective distortion to an image
     * @param Imagick $im The Imagick resource
     * @param Matrix $controlPoints Coordinates for applying the distortion
     * @return Imagick The Imagick resource
     */
    private static function applyPerspectiveDistortion(Imagick $im, Matrix $controlPoints): Imagick
    {
        $im = self::ensureTransparency($im);
        $im->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints->toCoordinatesArray(), true);
        return $im;
    }

    /**
     * Rotates an image
     * @param Imagick $im The Imagick resource
     * @param float $rotation Degrees by which to rotate the image
     * @return Imagick The Imagick resource
     */
    private static function rotateImage(Imagick $im, float $rotation): Imagick
    {
        $im->rotateImage(new ImagickPixel('none'), $rotation);
        return $im;
    }

    /**
     * Re-sizes an image
     * @param Imagick $im The Imagick resource
     * @param float $scale The ratio by which to resize the image
     * @return Imagick The Imagick resource
     * @throws ImagickException
     */
    private static function resizeImage(Imagick $im, float $scale): Imagick
    {
        $width = (float)$im->getImageWidth();
        $widthAdjusted = (int)floor($width * $scale);
        $im->scaleImage($widthAdjusted, 0);
        return $im;
    }

    /**
     * Named constructor for convenience. Uses the ImageBuilder to pass arguments to the constructor
     * @param ImageBuilder $imageBuilder The ImageBuilderInstance
     * @return ImageInterface An instance of self
     * @throws ImagickException
     */
    public static function build(ImageBuilder $imageBuilder): ImageInterface
    {
        return new Image(
            $imageBuilder->getIm(),
            $imageBuilder->getCoordinates(),
            $imageBuilder->getScale(),
            $imageBuilder->isFlip(),
            $imageBuilder->getRotation(),
            $imageBuilder->getControlPoints(),
            $imageBuilder->getMask()
        );
    }

    /**
     * (@inheritDoc)
     */
    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    /**
     * (@inheritDoc)
     */
    public function getResource(): Imagick
    {
        return $this->im;
    }

    /**
     * (@inheritDoc)
     */
    public function addOverlay(ImageInterface $overlayImage): void
    {
        $im = $overlayImage->getResource();
        $coordinates = $overlayImage->getCoordinates();
        $x = $coordinates->getX();
        $y = $coordinates->getY();
        $this->im->compositeImage($im, Imagick::COMPOSITE_DEFAULT, $x, $y);
    }
}
