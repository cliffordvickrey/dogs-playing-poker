<?php

declare(strict_types=1);

use Cliffordvickrey\DogsPlayingPoker\DogsPlayingPokerGenerator;

chdir(__DIR__);

require_once('../vendor/autoload.php');

call_user_func(function () {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0');
    header('Pragma: no-cache');

    ob_start();

    try {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? '');
        if ('POST' !== $method) {
            throw new RuntimeException('Unsupported method', 405);
        }

        $permutationId = filter_input(INPUT_POST, 'permutationId', FILTER_SANITIZE_NUMBER_INT);
        if (false === $permutationId) {
            $permutationId = null;
        }
        if (null !== $permutationId) {
            $permutationId = (int)$permutationId;
        }
        if (is_int($permutationId) && ($permutationId < 1 || $permutationId > 6622345729233223680)) {
            $permutationId = null;
        }

        $generator = DogsPlayingPokerGenerator::build();
        $dogsPlayingPoker = $generator->generate($permutationId);

        header('Content-Type: image/png');
        header(sprintf('Dogs-Playing-Poker-Id: %s', $dogsPlayingPoker->getPermutationId()));
        header(sprintf('Dogs-Playing-Poker-Cards: %s', implode(',', $dogsPlayingPoker->getCardIds())));

        ob_end_clean();
        echo $dogsPlayingPoker->getDogsAsBlob();
        exit(0);
    } catch (Throwable $e) {
        if (ob_get_contents()) {
            ob_end_clean();
        }

        $responseCode = 405 === $e->getCode() ? 405 : 500;
        http_response_code($responseCode);
        header('Content-Type: text/plain; charset=UTF-8');
        echo $e->getMessage();
        exit(1);
    }
});
