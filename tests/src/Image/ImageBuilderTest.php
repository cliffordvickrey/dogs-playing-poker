<?php

declare(strict_types=1);

namespace Test\Cliffordvickrey\DogsPlayingPoker\Image;

use Cliffordvickrey\DogsPlayingPoker\Exception\DogsPlayingPokerTypeException;
use Cliffordvickrey\DogsPlayingPoker\Image\ImageBuilder;
use Cliffordvickrey\DogsPlayingPoker\ValueObject\Coordinates;
use Cliffordvickrey\DogsPlayingPoker\ValueObject\Matrix;
use Imagick;
use PHPUnit\Framework\TestCase;

class ImageBuilderTest extends TestCase
{
    private $config;
    private $im;

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

        $this->im = new Imagick();
        $this->im->newPseudoImage(100, 100, 'pattern:checkerboard');
    }

    public function testFromImageAndConfig(): void
    {
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertInstanceOf(ImageBuilder::class, $imageBuilder);
    }

    public function testFromImageAndConfigBadX(): void
    {
        $this->config['x'] = 'blah';
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

    public function testFromImageAndConfigBadY(): void
    {
        $this->config['y'] = 'blah';
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

    public function testFromImageAndConfigBadScale(): void
    {
        $this->config['scale'] = 'blah';
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

    public function testFromImageAndConfigBadFlip(): void
    {
        $this->config['flip'] = 'blah';
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

    public function testFromImageAndConfigBadRotation(): void
    {
        $this->config['rotation'] = 'blah';
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

    public function testFromImageAndConfigBadDeltaX(): void
    {
        $this->config['distortion']['topLeft']['x'] = 'blah';
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

    public function testFromImageAndConfigBadDeltaY(): void
    {
        $this->config['distortion']['topLeft']['y'] = 'blah';
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

    public function testFromImageAndConfigBadDelta(): void
    {
        unset($this->config['distortion']['topLeft']);
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

    public function testFromImageAndConfigBadMaskX(): void
    {
        $this->config['mask'][0]['x'] = 'blah';
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

    public function testFromImageAndConfigBadMaskY(): void
    {
        $this->config['mask'][0]['y'] = 'blah';
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

    public function testGetIm(): void
    {
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertInstanceOf(Imagick::class, $imageBuilder->getIm());
    }

    public function testGetCoordinates(): void
    {
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $coordinates = $imageBuilder->getCoordinates() ?? new Coordinates(0, 0);
        $this->assertEquals(544, $coordinates->getX());
        $this->assertEquals(342, $coordinates->getY());
    }

    public function testGetCoordinatesNull(): void
    {
        unset($this->config['x']);
        unset($this->config['y']);
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertNull($imageBuilder->getCoordinates());
    }

    public function testGetScale(): void
    {
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertEquals(0.35, $imageBuilder->getScale());
    }

    public function testGetScaleNull(): void
    {
        unset($this->config['scale']);
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertNull($imageBuilder->getScale());
    }

    public function testGetRotation(): void
    {
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertEquals(-108.0, $imageBuilder->getRotation());
    }

    public function testGetRotationNull(): void
    {
        unset($this->config['rotation']);
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertNull($imageBuilder->getRotation());
    }

    public function testIsFlip(): void
    {
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertTrue($imageBuilder->isFlip());
        $this->config['flip'] = false;
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertFalse($imageBuilder->isFlip());
        unset($this->config['flip']);
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertFalse($imageBuilder->isFlip());
    }

    public function testGetControlPoints(): void
    {
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $controlPoints = $imageBuilder->getControlPoints() ?? new Matrix();
        $controlPoints = $controlPoints->toCoordinatesArray();

        $this->assertCount(16, $controlPoints);
        $this->assertEquals(0, $controlPoints[0]);
        $this->assertEquals(0, $controlPoints[1]);
        $this->assertEquals(-10, $controlPoints[2]);
        $this->assertEquals(-30, $controlPoints[3]);
        $this->assertEquals(100, $controlPoints[4]);
        $this->assertEquals(0, $controlPoints[5]);
        $this->assertEquals(90, $controlPoints[6]);
        $this->assertEquals(-40, $controlPoints[7]);
        $this->assertEquals(100, $controlPoints[8]);
        $this->assertEquals(100, $controlPoints[9]);
        $this->assertEquals(80, $controlPoints[10]);
        $this->assertEquals(130, $controlPoints[11]);
        $this->assertEquals(0, $controlPoints[12]);
        $this->assertEquals(100, $controlPoints[13]);
        $this->assertEquals(-20, $controlPoints[14]);
        $this->assertEquals(120, $controlPoints[15]);
    }

    public function testGetControlPointsNull(): void
    {
        unset($this->config['distortion']);
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertNull($imageBuilder->getControlPoints());
    }

    public function testGetMask(): void
    {
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $mask = $imageBuilder->getMask() ?? new Matrix();
        $mask = $mask->toCoordinatesArray();
        $this->assertCount(8, $mask);
        $this->assertEquals(40, $mask[0]);
        $this->assertEquals(35, $mask[1]);
        $this->assertEquals(40, $mask[2]);
        $this->assertEquals(70, $mask[3]);
        $this->assertEquals(50, $mask[4]);
        $this->assertEquals(70, $mask[5]);
        $this->assertEquals(50, $mask[6]);
        $this->assertEquals(35, $mask[7]);
    }

    public function testGetMaskNull(): void
    {
        unset($this->config['mask']);
        $imageBuilder = ImageBuilder::fromImageAndConfig($this->im, $this->config);
        $this->assertNull($imageBuilder->getMask());
    }

    public function testFromImageAndConfigWrongMaskType(): void
    {
        $this->config['mask'] = 'blah';
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

    public function testFromImageAndConfigWrongDistortionType(): void
    {
        $this->config['distortion'] = 'blah';
        $this->expectException(DogsPlayingPokerTypeException::class);
        ImageBuilder::fromImageAndConfig($this->im, $this->config);
    }

}