<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker\Image;

use Cliffordvickrey\DogsPlayingPoker\Exception\DogsPlayingPokerTypeException;
use Cliffordvickrey\DogsPlayingPoker\ValueObject\Coordinates;
use Cliffordvickrey\DogsPlayingPoker\ValueObject\Matrix;
use Imagick;
use function array_combine;
use function array_keys;
use function array_map;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function sprintf;

/**
 * Parses an image and configuration array, and encapsulates the results. See Image::build()
 */
final class ImageBuilder
{
    /** @var Imagick */
    private $im;
    /** @var Coordinates|null */
    private $coordinates;
    /** @var float|null */
    private $scale;
    /** @var bool */
    private $flip = false;
    /** @var float|null */
    private $rotation;
    /** @var Matrix|null */
    private $controlPoints;
    /** @var Matrix|null */
    private $mask;

    /**
     * Named constructor
     * @param Imagick $im The Imagick resource
     * @param array $config Card destination configuration
     * @return ImageBuilder An instance of self
     */
    public static function fromImageAndConfig(Imagick $im, array $config): ImageBuilder
    {
        $x = $config['x'] ?? null;
        $y = $config['y'] ?? null;
        $scale = $config['scale'] ?? null;
        $flip = $config['flip'] ?? null;
        $rotation = $config['rotation'] ?? null;
        $distortionConfig = $config['distortion'] ?? null;
        $maskConfig = $config['mask'] ?? null;

        if (null !== $x && !is_int($x)) {
            throw DogsPlayingPokerTypeException::fromVariable('$config["x"]', 'int or NULL', $x);
        }

        if (null !== $y && !is_int($y)) {
            throw DogsPlayingPokerTypeException::fromVariable('$config["y"]', 'int or NULL', $y);
        }

        if (null !== $scale && !is_float($scale)) {
            throw DogsPlayingPokerTypeException::fromVariable('$config["scale"]', 'float or NULL', $scale);
        }

        if (null !== $flip && !is_bool($flip)) {
            throw DogsPlayingPokerTypeException::fromVariable('$config["flip"]', 'boolean or NULL', $flip);
        }

        if (null !== $rotation && !is_float($rotation)) {
            throw DogsPlayingPokerTypeException::fromVariable('$config["rotation"]', 'float or NULL', $rotation);
        }

        if (null !== $distortionConfig && !is_array($distortionConfig)) {
            throw DogsPlayingPokerTypeException::fromVariable(
                '$config["distortion"]',
                'array or NULL',
                $distortionConfig
            );
        }

        if (null !== $maskConfig && !is_array($maskConfig)) {
            throw DogsPlayingPokerTypeException::fromVariable(
                '$config["mask"]',
                'array or NULL',
                $maskConfig
            );
        }

        $coordinates = null;
        if (null !== $x && null !== $y) {
            $coordinates = new Coordinates($x, $y);
        }

        $controlPoints = null;
        if (is_array($distortionConfig)) {
            $controlPoints = self::buildControlPointsMatrix($im, $distortionConfig, $flip ?? false);
        }

        $mask = null;
        if (is_array($maskConfig)) {
            $mask = self::buildMask($maskConfig);
        }

        $self = new self();
        $self->im = $im;

        if (null !== $coordinates) {
            $self->coordinates = $coordinates;
        }

        if (null !== $scale) {
            $self->scale = $scale;
        }

        if (null !== $flip) {
            $self->flip = $flip;
        }

        if (null !== $rotation) {
            $self->rotation = $rotation;
        }

        if (null !== $controlPoints) {
            $self->controlPoints = $controlPoints;
        }

        if (null !== $mask) {
            $self->mask = $mask;
        }

        return $self;
    }

    /**
     * Parses an array of control point deltas (see ConfigProvider) and returns a matrix of coordinates to pass to
     * Imagick@distortImage
     * @param Imagick $im The Imagick resource
     * @param array $distortionConfig Perspective distortion configuration
     * @param bool $flip Whether or not to flip the image
     * @return Matrix
     */
    private static function buildControlPointsMatrix(Imagick $im, array $distortionConfig, bool $flip): Matrix
    {
        $deltas = self::extractDeltas($distortionConfig);
        if ($flip) {
            $deltas = self::flipDeltas($deltas);
        }

        $width = $im->getImageWidth();
        $height = $im->getImageHeight();

        $points = [];

        foreach ($deltas as $i => $delta) {
            $deltaX = $delta['x'] ?? null;
            if (!is_int($deltaX)) {
                throw DogsPlayingPokerTypeException::fromVariable('$delta["x"]', 'int', $deltaX);
            }

            $deltaY = $delta['y'] ?? null;
            if (!is_int($deltaY)) {
                throw DogsPlayingPokerTypeException::fromVariable('$delta["y"]', 'int', $deltaY);
            }

            switch ($i) {
                case 0: // top left
                    $sourceCoordinates = new Coordinates(0, 0);
                    break;
                case 1: // top right
                    $sourceCoordinates = new Coordinates($width, 0);
                    break;
                case 2: // bottom right
                    $sourceCoordinates = new Coordinates($width, $height);
                    break;
                default: // bottom left
                    $sourceCoordinates = new Coordinates(0, $height);
                    break;
            }

            $destinationX = $deltaX + $sourceCoordinates->getX();
            $destinationY = $deltaY + $sourceCoordinates->getY();
            $destinationCoordinates = new Coordinates($destinationX, $destinationY);

            $points[] = $sourceCoordinates;
            $points[] = $destinationCoordinates;
        }

        return new Matrix(...$points);
    }

    /**
     * Validates the control point deltas provided by the ConfigProvider class and converts them into a simple numeric
     * array
     * @param array $distortionConfig An associative array of control point deltas
     * @return array A list of deltas
     */
    private static function extractDeltas(array $distortionConfig): array
    {
        $keys = ['topLeft', 'topRight', 'bottomRight', 'bottomLeft'];

        $deltas = [];
        foreach ($keys as $key) {
            $delta = $distortionConfig[$key] ?? null;
            if (!is_array($delta)) {
                throw DogsPlayingPokerTypeException::fromVariable(
                    sprintf('$distortionConfig["%s"]', $key), 'array', $delta
                );
            }
            $deltas[] = $delta;
        }

        return $deltas;
    }

    /**
     * Transforms distortion deltas for flipped cards
     * @param array $deltas Deltas to flip
     * @return array Flipped deltas
     */
    private static function flipDeltas(array $deltas): array
    {
        $keys = [2, 3, 0, 1];
        $flipped = [];
        foreach ($keys as $key) {
            $flipped[] = array_combine(
                array_keys($deltas[$key]),
                array_map(function ($point) {
                    if (!is_int($point)) {
                        return $point;
                    }

                    return $point * -1;
                }, $deltas[$key])
            ) ?: [];
        }
        return $flipped;
    }

    /**
     * Validates the mask configuration provided by the ConfigProvider and builds a matrix of polygonal coordinates
     * @param array $maskConfig The mask configuration
     * @return Matrix The matrix of coordinates
     */
    private static function buildMask(array $maskConfig): Matrix
    {
        $points = [];

        foreach ($maskConfig as $point) {
            $x = $point['x'] ?? null;
            if (!is_int($x)) {
                throw DogsPlayingPokerTypeException::fromVariable('$point["x"]', 'int', $x);
            }

            $y = $point['y'] ?? null;
            if (!is_int($y)) {
                throw DogsPlayingPokerTypeException::fromVariable('$point["y"]', 'int', $y);
            }

            $points[] = new Coordinates($x, $y);
        }

        return new Matrix(...$points);
    }

    /**
     * @return Imagick
     */
    public function getIm(): Imagick
    {
        return $this->im;
    }

    /**
     * @return Coordinates|null
     */
    public function getCoordinates(): ?Coordinates
    {
        return $this->coordinates;
    }

    /**
     * @return float|null
     */
    public function getScale(): ?float
    {
        return $this->scale;
    }

    /**
     * @return Matrix|null
     */
    public function getControlPoints(): ?Matrix
    {
        return $this->controlPoints;
    }

    /**
     * @return float|null
     */
    public function getRotation(): ?float
    {
        return $this->rotation;
    }

    /**
     * @return bool
     */
    public function isFlip(): bool
    {
        return $this->flip;
    }

    /**
     * @return Matrix|null
     */
    public function getMask(): ?Matrix
    {
        return $this->mask;
    }
}
