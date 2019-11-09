<?php

declare(strict_types=1);

namespace Test\Cliffordvickrey\DogsPlayingPoker\Image;

use Cliffordvickrey\DogsPlayingPoker\Image\Image;
use Cliffordvickrey\DogsPlayingPoker\Image\ImageBuilder;
use Cliffordvickrey\DogsPlayingPoker\Image\ImageInterface;
use Imagick;
use ImagickException;
use PHPStan\Testing\TestCase;

class ImageTest extends TestCase
{
    private $config;
    private $imageBuilder;

    public function setUp(): void
    {
        $this->config = [
            'x' => 544,
            'y' => 342,
            'scale' => .35,
            'rotation' => -108.0,
            'flip' => true,
            'distortion' => [
                'topLeft' => ['x' => 20, 'y' => -30],
                'topRight' => ['x' => 20, 'y' => -20],
                'bottomRight' => ['x' => 10, 'y' => 30],
                'bottomLeft' => ['x' => 10, 'y' => 40]
            ],
            'mask' => [
                ['x' => 40, 'y' => 35],
                ['x' => 40, 'y' => 70],
                ['x' => 50, 'y' => 70],
                ['x' => 50, 'y' => 35]
            ]
        ];

        $im = new Imagick();
        $im->newPseudoImage(100, 100, 'pattern:checkerboard');
        $this->imageBuilder = ImageBuilder::fromImageAndConfig($im, $this->config);
    }

    /**
     * @throws ImagickException
     */
    public function testConstruct(): void
    {
        $image = new Image(
            $this->imageBuilder->getIm(),
            $this->imageBuilder->getCoordinates(),
            $this->imageBuilder->getScale(),
            $this->imageBuilder->isFlip(),
            $this->imageBuilder->getRotation(),
            $this->imageBuilder->getControlPoints()
        );

        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    /**
     * @throws ImagickException
     */
    public function testBuild(): void
    {
        $image1 = new Image(
            $this->imageBuilder->getIm(),
            $this->imageBuilder->getCoordinates(),
            $this->imageBuilder->getScale(),
            $this->imageBuilder->isFlip(),
            $this->imageBuilder->getRotation(),
            $this->imageBuilder->getControlPoints()
        );

        $image2 = Image::build($this->imageBuilder);

        $this->assertEquals(
            $image1->getCoordinates()->getX(),
            $image2->getCoordinates()->getX()
        );

        $this->assertEquals(
            $image1->getCoordinates()->getY(),
            $image2->getCoordinates()->getY()
        );

        $this->assertEquals(
            $image1->getResource()->getImageLength(),
            $image2->getResource()->getImageLength()
        );
    }

    /**
     * @throws ImagickException
     */
    public function testGetCoordinates(): void
    {
        $image = Image::build($this->imageBuilder);
        $coordinates = $image->getCoordinates();
        $this->assertEquals(544, $coordinates->getX());
        $this->assertEquals(342, $coordinates->getY());
    }

    /**
     * @throws ImagickException
     */
    public function testGetResource(): void
    {
        $image = Image::build($this->imageBuilder);
        $this->assertInstanceOf(Imagick::class, $image->getResource());
    }

    /**
     * @throws ImagickException
     */
    public function testAddOverlay(): void
    {
        $image1 = Image::build($this->imageBuilder);

        $im = new Imagick();
        $im->newPseudoImage(500, 500, 'plasma:fractal');

        $image2 = new Image($im);
        $blobOriginal = $image2->getResource()->getImageLength();
        $image2->addOverlay($image1);
        $blob = $image2->getResource()->getImageBlob();
        $this->assertNotEquals($blobOriginal, $blob);
    }
}
