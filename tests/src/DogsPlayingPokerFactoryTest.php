<?php

declare(strict_types=1);

namespace Test\Cliffordvickrey\DogsPlayingPoker;

use Cliffordvickrey\DogsPlayingPoker\Config\ConfigProvider;
use Cliffordvickrey\DogsPlayingPoker\DogsPlayingPokerFactory;
use Cliffordvickrey\DogsPlayingPoker\DogsPlayingPokerGeneratorInterface;
use Cliffordvickrey\DogsPlayingPoker\Exception\DogsPlayingPokerTypeException;
use PHPUnit\Framework\TestCase;

class DogsPlayingPokerFactoryTest extends TestCase
{
    private $config;

    public function setUp(): void
    {
        $this->config = (new ConfigProvider())();
    }

    public function testInvoke(): void
    {
        $factory = new DogsPlayingPokerFactory();
        $object = $factory();
        $this->assertInstanceOf(DogsPlayingPokerGeneratorInterface::class, $object);
    }

    public function testInvokeWithConfig(): void
    {
        $factory = new DogsPlayingPokerFactory();
        $object = $factory($this->config);
        $this->assertInstanceOf(DogsPlayingPokerGeneratorInterface::class, $object);
    }

    public function testInvokeWithBadCardFileName(): void
    {
        $factory = new DogsPlayingPokerFactory();
        unset($this->config[ConfigProvider::class]['cardsFileName']);
        $this->expectException(DogsPlayingPokerTypeException::class);
        $factory($this->config);
    }

    public function testInvokeWithBadDogsPlayingPokerFileName(): void
    {
        $factory = new DogsPlayingPokerFactory();
        unset($this->config[ConfigProvider::class]['dogsPlayingPokerFileName']);
        $this->expectException(DogsPlayingPokerTypeException::class);
        $factory($this->config);
    }

    public function testInvokeWithBadDogsCardSource(): void
    {
        $factory = new DogsPlayingPokerFactory();
        unset($this->config[ConfigProvider::class]['cardSource']);
        $this->expectException(DogsPlayingPokerTypeException::class);
        $factory($this->config);
    }

    public function testInvokeWithBadDogsCardDestination(): void
    {
        $factory = new DogsPlayingPokerFactory();
        unset($this->config[ConfigProvider::class]['cardDestination']);
        $this->expectException(DogsPlayingPokerTypeException::class);
        $factory($this->config);
    }
}