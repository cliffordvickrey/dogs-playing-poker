<?php

declare(strict_types=1);

namespace Test\Cliffordvickrey\DogsPlayingPoker;

use Cliffordvickrey\DogsPlayingPoker\DogsPlayingPoker;
use Imagick;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use function fclose;
use function fopen;
use function is_resource;
use function rewind;
use function stream_get_contents;
use function strlen;

class DogsPlayingPokerTest extends TestCase
{
    public function testGetDogsAsBlob(): void
    {
        $im = new Imagick();
        $im->newPseudoImage(100, 100, 'pattern:checkerboard');
        $im->setImageFormat('png');
        $dogs = new DogsPlayingPoker($im, 1, [0]);
        $blob = $dogs->getDogsAsBlob();
        $this->assertGreaterThan(0, strlen($blob));
    }

    public function testWriteBlobToResource(): void
    {
        $im = new Imagick();
        $im->newPseudoImage(100, 100, 'pattern:checkerboard');
        $im->setImageFormat('png');
        $dogs = new DogsPlayingPoker($im, 1, [0]);
        $resource = fopen('php://temp', 'w+');
        if (!is_resource($resource)) {
            throw new RuntimeException('Failed to open temporary stream');
        }
        $dogs->writeDogsToResource($resource);
        rewind($resource);
        $this->assertGreaterThan(0, strlen(stream_get_contents($resource) ?: ''));
        fclose($resource);
    }

    public function testGetCardIds(): void
    {
        $im = new Imagick();
        $im->newPseudoImage(100, 100, 'pattern:checkerboard');
        $im->setImageFormat('png');
        $dogs = new DogsPlayingPoker($im, 1, [1, 2, 3]);
        $this->assertEquals([1, 2, 3], $dogs->getCardIds());
    }

    public function testGetPermutationId(): void
    {
        $im = new Imagick();
        $im->newPseudoImage(100, 100, 'pattern:checkerboard');
        $im->setImageFormat('png');
        $dogs = new DogsPlayingPoker($im, 100, [1, 2, 3]);
        $this->assertEquals(100, $dogs->getPermutationId());
    }
}
