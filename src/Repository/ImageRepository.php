<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker\Repository;

use Cliffordvickrey\DogsPlayingPoker\Exception\DogsPlayingPokerOutOfBoundsException;
use Cliffordvickrey\DogsPlayingPoker\Exception\DogsPlayingPokerTypeException;
use Cliffordvickrey\DogsPlayingPoker\Image\Image;
use Cliffordvickrey\DogsPlayingPoker\Image\ImageBuilder;
use Cliffordvickrey\DogsPlayingPoker\Image\ImageInterface;
use Imagick;
use ImagickException;
use function array_key_exists;
use function is_array;
use function is_int;
use function sprintf;

class ImageRepository implements ImageRepositoryInterface
{
    private $cardsFileName;
    private $dogsPlayingPokerFileName;
    private $cardSourceConfig;
    private $cardDestinationConfig;
    /** @var Imagick|null */
    private $im;

    public function __construct(
        string $cardsFileName,
        string $dogsPlayingPokerFileName,
        array $cardSourceConfig,
        array $cardDestinationConfig
    )
    {
        $this->cardsFileName = $cardsFileName;
        $this->dogsPlayingPokerFileName = $dogsPlayingPokerFileName;
        $this->cardSourceConfig = $cardSourceConfig;
        $this->cardDestinationConfig = $cardDestinationConfig;
    }

    /**
     * (@inheritDoc)
     * @throws ImagickException
     */
    public function getDogsPlayingPokerImage(): ImageInterface
    {
        $im = new Imagick();
        $im->readImage($this->dogsPlayingPokerFileName);
        return new Image($im);
    }

    /**
     * (@inheritDoc)
     * @throws ImagickException
     */
    public function getCardImage(int $sourceId, int $destinationId): ImageInterface
    {
        $sourceConfig = $this->getCardSourceConfig($sourceId);
        $destinationConfig = $this->getCardDestinationConfig($destinationId);

        $width = $sourceConfig['width'] ?? null;
        $height = $sourceConfig['height'] ?? null;
        $x = $sourceConfig['x'] ?? null;
        $y = $sourceConfig['y'] ?? null;

        if (!is_int($x)) {
            throw DogsPlayingPokerTypeException::fromVariable('$sourceConfig["x"]', 'int', $x);
        }

        if (!is_int($y)) {
            throw DogsPlayingPokerTypeException::fromVariable('$sourceConfig["y"]', 'int', $y);
        }

        if (!is_int($width)) {
            throw DogsPlayingPokerTypeException::fromVariable('$sourceConfig["width"]', 'int', $width);
        }

        if (!is_int($height)) {
            throw DogsPlayingPokerTypeException::fromVariable('$sourceConfig["height"]', 'int', $height);
        }

        $im = clone $this->getCardResource();
        $im->cropImage($width, $height, $x, $y);
        $im->setImagePage(0, 0, 0, 0);

        $imageBuilder = ImageBuilder::fromImageAndConfig($im, $destinationConfig);
        return Image::build($imageBuilder);
    }

    /**
     * @param int $id The card ID
     * @return array The card source configuration
     */
    private function getCardSourceConfig(int $id): array
    {
        if (!array_key_exists($id, $this->cardSourceConfig)) {
            throw new DogsPlayingPokerOutOfBoundsException(sprintf('Invalid source ID, "%d"', $id));
        }

        $config = $this->cardSourceConfig[$id];
        if (!is_array($config)) {
            throw DogsPlayingPokerTypeException::fromVariable(sprintf('$config[%d]', $id), 'array', $config);
        }
        return $config;
    }

    /**
     * @param int $id The card destination ID
     * @return array The card destination configuration
     */
    private function getCardDestinationConfig(int $id): array
    {
        if (!array_key_exists($id, $this->cardDestinationConfig)) {
            throw new DogsPlayingPokerOutOfBoundsException(sprintf('Invalid destination ID, "%d"', $id));
        }

        $config = $this->cardDestinationConfig[$id];
        if (!is_array($config)) {
            throw DogsPlayingPokerTypeException::fromVariable(sprintf('$config[%d]', $id), 'array', $config);
        }
        return $config;
    }

    /**
     * Fetch and cache the image of all the cards
     * @return Imagick The image resource
     * @throws ImagickException
     */
    private function getCardResource(): Imagick
    {
        if (null === $this->im) {
            $this->im = new Imagick();
            $this->im->readImage($this->cardsFileName);
        }
        return $this->im;
    }
}