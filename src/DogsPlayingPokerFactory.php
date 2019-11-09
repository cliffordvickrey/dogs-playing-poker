<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker;

use Cliffordvickrey\DogsPlayingPoker\Config\ConfigProvider;
use Cliffordvickrey\DogsPlayingPoker\Exception\DogsPlayingPokerTypeException;
use Cliffordvickrey\DogsPlayingPoker\Math\Permutation;
use Cliffordvickrey\DogsPlayingPoker\Math\PermutationInterface;
use Cliffordvickrey\DogsPlayingPoker\Repository\ImageRepository;
use Cliffordvickrey\DogsPlayingPoker\Repository\ImageRepositoryInterface;
use function count;
use function is_array;
use function is_string;

/**
 * Functor that builds the Dogs Playing Poker generator
 */
class DogsPlayingPokerFactory
{
    /**
     * @param array|null $config The generator configuration, or NULL for library defaults
     * @return DogsPlayingPokerGeneratorInterface The constructed object
     */
    public function __invoke(?array $config = null): DogsPlayingPokerGeneratorInterface
    {
        if (null === $config) {
            $config = (new ConfigProvider())();
        }

        $scopedConfig = $config[ConfigProvider::class] ?? null;
        if (!is_array($scopedConfig)) {
            throw DogsPlayingPokerTypeException::fromVariable(
                '$config[' . ConfigProvider::class . ']', 'array', $scopedConfig
            );
        }

        $imageRepository = self::buildImageRepository($scopedConfig);
        $permutation = self::buildPermutation($scopedConfig);

        return new DogsPlayingPokerGenerator($imageRepository, $permutation);
    }

    private static function buildImageRepository(array $config): ImageRepositoryInterface
    {
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

        return new ImageRepository(
            $cardsFileName,
            $dogsPlayingPokerFileName,
            $cardSourceConfig,
            $cardDestinationConfig
        );
    }

    private static function buildPermutation(array $config): PermutationInterface
    {
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

        return new Permutation(52, count($cardDestinationConfig));
    }

}