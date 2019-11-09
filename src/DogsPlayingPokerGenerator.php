<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker;

use Cliffordvickrey\DogsPlayingPoker\Image\ImageInterface;
use Cliffordvickrey\DogsPlayingPoker\Math\PermutationInterface;
use Cliffordvickrey\DogsPlayingPoker\Repository\ImageRepositoryInterface;
use function rand;

final class DogsPlayingPokerGenerator implements DogsPlayingPokerGeneratorInterface
{
    private $imageRepository;
    private $permutation;

    public function __construct(ImageRepositoryInterface $imageRepository, PermutationInterface $permutation)
    {
        $this->imageRepository = $imageRepository;
        $this->permutation = $permutation;
    }

    /**
     * Convenience method for constructing the Dog Playing Poker generator instance with the default configuration
     * @param array|null $config Configuration array, or NULL for the library defaults
     * @return DogsPlayingPokerGeneratorInterface
     */
    public static function build(?array $config = null): DogsPlayingPokerGeneratorInterface
    {
        return (new DogsPlayingPokerFactory())($config);
    }

    /**
     * (@inheritDoc)
     */
    public function generate(?int $id = null): DogsPlayingPokerInterface
    {
        if (null === $id) {
            $id = $this->generateRandomId();
        }

        $permutation = $this->permutation->getPermutationById($id);

        $image = $this->imageRepository->getDogsPlayingPokerImage();

        $cards = $this->getCards($permutation);
        foreach ($cards as $card) {
            $image->addOverlay($card);
        }

        return new DogsPlayingPoker($image->getResource(), $id, $permutation);
    }

    /**
     * Generates a random permutation ID for a Dogs Playing Poker painting
     * @return int The ID
     */
    private function generateRandomId(): int
    {
        $max = $this->permutation->getPermutationCount();
        return rand(1, $max);
    }

    /**
     * Builds an array of images from an list of card IDs
     * @param int[] $permutation A list of card IDs
     * @return ImageInterface[] A list of images
     */
    private function getCards(array $permutation): array
    {
        $cards = [];
        foreach ($permutation as $i => $id) {
            $cards[] = $this->imageRepository->getCardImage($id, $i);
        }
        return $cards;
    }
}
