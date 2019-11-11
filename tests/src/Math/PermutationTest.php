<?php

declare(strict_types=1);

namespace Test\Cliffordvickrey\DogsPlayingPoker\Math;

use Cliffordvickrey\DogsPlayingPoker\Exception\DogsPlayingPokerExceptionInterface;
use Cliffordvickrey\DogsPlayingPoker\Math\Permutation;
use PHPUnit\Framework\TestCase;
use function array_filter;
use function array_map;
use function array_unique;
use function count;
use function implode;

class PermutationTest extends TestCase
{
    public function testConstructWithInvalidArguments(): void
    {
        $this->expectException(DogsPlayingPokerExceptionInterface::class);
        new Permutation(5, 6);
    }

    public function testGetNumber(): void
    {
        $permutation = new Permutation(6, 5);
        $this->assertEquals(6, $permutation->getNumber());
    }

    public function testGetNumberChosen(): void
    {
        $permutation = new Permutation(6, 5);
        $this->assertEquals(5, $permutation->getNumberChosen());
    }

    public function testGetPossiblePermutationCount(): void
    {
        $cardCombiner = new Permutation(52, 12);
        $count = $cardCombiner->getPermutationCount();
        $this->assertEquals(6622345729233223680, $count);
    }

    public function testGetPermutation(): void
    {
        $cardCombiner = new Permutation(10, 4);

        $permutations = [];
        for ($i = 1; $i <= 5040; $i++) {
            $permutations[] = $cardCombiner->getPermutationById($i);
        }

        // are all the digits in each permutation unique?
        $originalCount = count($permutations);
        $permutations = array_filter($permutations, function (array $permutation): bool {
            if (count($permutation) !== 4) {
                return false;
            }

            $uniqueNumbers = array_unique($permutation);
            return count($uniqueNumbers) === 4;
        });
        $this->assertCount($originalCount, $permutations);

        // are there as many permutations as we expect?
        $permutations = array_map(function (array $permutation): string {
            return implode('', $permutation);
        }, $permutations);
        $this->assertCount(5040, array_unique($permutations));
    }

    public function testGetPermutationIdLessThanOne(): void
    {
        $cardCombiner = new Permutation(10, 4);
        $this->expectException(DogsPlayingPokerExceptionInterface::class);
        $cardCombiner->getPermutationById(0);
    }

    public function testGetPermutationIdOutOfBounds(): void
    {
        $cardCombiner = new Permutation(10, 4);
        $this->expectException(DogsPlayingPokerExceptionInterface::class);
        $cardCombiner->getPermutationById(5041);
    }
}
