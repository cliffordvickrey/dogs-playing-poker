<?php

declare(strict_types=1);

namespace Test\Cliffordvickrey\DogsPlayingPoker\Repository;

use Cliffordvickrey\DogsPlayingPoker\Config\ConfigProvider;
use Cliffordvickrey\DogsPlayingPoker\Exception\DogsPlayingPokerTypeException;
use Cliffordvickrey\DogsPlayingPoker\Image\ImageInterface;
use Cliffordvickrey\DogsPlayingPoker\Repository\ImageRepository;
use Cliffordvickrey\DogsPlayingPoker\Repository\ImageRepositoryInterface;
use PHPUnit\Framework\TestCase;
use function is_array;
use function is_string;

class ImageRepositoryTest extends TestCase
{
    /** @var ImageRepositoryInterface */
    private $repository;

    public function setUp(): void
    {
        $this->repository = self::buildImageRepository((new ConfigProvider())());
    }

    private static function buildImageRepository(array $config): ImageRepositoryInterface
    {
        $config = $config[ConfigProvider::class] ?? null;
        if (!is_array($config)) {
            throw DogsPlayingPokerTypeException::fromVariable(
                '$config[' . ConfigProvider::class. ']', 'array', $config
            );
        }

        $cardsFileName = $config['cardsFileName'] ?? null;
        if (!is_string($cardsFileName)) {
            throw DogsPlayingPokerTypeException::fromVariable('$config["cardsFileName"]', 'string', $cardsFileName);
        }

        $dogsPlayingPokerFileName = $config['dogsPlayingPokerFileName'] ?? null;
        if (!is_string($dogsPlayingPokerFileName)) {
            throw DogsPlayingPokerTypeException::fromVariable(
                '$config["dogsPlayingPokerFileName"]',
                'string',
                $dogsPlayingPokerFileName
            );
        }

        $cardSourceConfig = $config['cardSource'] ?? null;
        if (!is_array($cardSourceConfig)) {
            throw DogsPlayingPokerTypeException::fromVariable('$config["cardSource"]', 'array', $cardSourceConfig);
        }

        $cardDestinationConfig = $config['cardDestination'] ?? null;
        if (!is_array($cardDestinationConfig)) {
            throw DogsPlayingPokerTypeException::fromVariable(
                '$config["cardDestination"]', 'array', $cardDestinationConfig
            );
        }

        unset($cardSourceConfig[1]['x']);
        unset($cardSourceConfig[2]['y']);
        unset($cardSourceConfig[3]['width']);
        unset($cardSourceConfig[4]['height']);

        return new ImageRepository(
            $cardsFileName,
            $dogsPlayingPokerFileName,
            $cardSourceConfig,
            $cardDestinationConfig
        );
    }

    public function testGetDogsPlayingPokerImage(): void
    {
        $image = $this->repository->getDogsPlayingPokerImage();
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testGetCardImage(): void
    {
        $cardImage = $this->repository->getCardImage(0, 0);
        $coordinates = $cardImage->getCoordinates();
        $this->assertEquals(272, $coordinates->getX());
        $this->assertEquals(333, $coordinates->getY());
    }

    public function testGetCardImageSourceOutOfBounds(): void
    {
        $this->expectExceptionMessage('Invalid source ID, "52"');
        $this->repository->getCardImage(52, 0);
    }

    public function testGetCardImageDestinationOutOfBounds(): void
    {
        $this->expectExceptionMessage('Invalid destination ID, "12"');
        $this->repository->getCardImage(0, 12);
    }

    public function testGetCardImageBadX(): void
    {
        $this->expectException(DogsPlayingPokerTypeException::class);
        $this->repository->getCardImage(1, 0);
    }

    public function testGetCardImageBadY(): void
    {
        $this->expectException(DogsPlayingPokerTypeException::class);
        $this->repository->getCardImage(2, 0);
    }

    public function testGetCardImageBadWidth(): void
    {
        $this->expectException(DogsPlayingPokerTypeException::class);
        $this->repository->getCardImage(3, 0);
    }

    public function testGetCardImageBadHeight(): void
    {
        $this->expectException(DogsPlayingPokerTypeException::class);
        $this->repository->getCardImage(4, 0);
    }
}