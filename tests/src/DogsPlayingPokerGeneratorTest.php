<?php

declare(strict_types=1);

namespace Test\Cliffordvickrey\DogsPlayingPoker;

use Cliffordvickrey\DogsPlayingPoker\DogsPlayingPokerGenerator;
use Cliffordvickrey\DogsPlayingPoker\DogsPlayingPokerGeneratorInterface;
use Cliffordvickrey\DogsPlayingPoker\DogsPlayingPokerInterface;
use Cliffordvickrey\DogsPlayingPoker\Exception\DogsPlayingPokerExceptionInterface;
use PHPUnit\Framework\TestCase;

class DogsPlayingPokerGeneratorTest extends TestCase
{
    public function testBuild(): void
    {
        $generator = DogsPlayingPokerGenerator::build();
        $this->assertInstanceOf(DogsPlayingPokerGeneratorInterface::class, $generator);
    }

    public function testBuildWithBadConfig(): void
    {
        $this->expectException(DogsPlayingPokerExceptionInterface::class);
        DogsPlayingPokerGenerator::build(["I'll buy that for" => 'a dollar!']);
    }

    public function testGenerate(): void
    {
        $generator = DogsPlayingPokerGenerator::build();
        $dogsPlayingPoker = $generator->generate(1);
        $this->assertInstanceOf(DogsPlayingPokerInterface::class, $dogsPlayingPoker);
        $this->assertEquals(1, $dogsPlayingPoker->getPermutationId());
    }

    public function testGenerateRandom(): void
    {
        $generator = DogsPlayingPokerGenerator::build();
        $dogsPlayingPoker = $generator->generate();
        $this->assertInstanceOf(DogsPlayingPokerInterface::class, $dogsPlayingPoker);
        $this->assertGreaterThan(0, $dogsPlayingPoker->getPermutationId());
        $this->assertLessThanOrEqual(6622345729233223680, $dogsPlayingPoker->getPermutationId());
    }

}
