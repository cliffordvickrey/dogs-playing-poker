<?php

declare(strict_types=1);

namespace Cliffordvickrey\DogsPlayingPoker\Config;

/**
 * Provides the base configuration for using the Dogs Playing Poker library
 */
class ConfigProvider
{
    /**
     * Returns the base configuration array (namespaced so that it can be merged in a global application configuration)
     * @return array
     */
    public function __invoke()
    {
        return [self::class => $this->getConfig()];
    }

    /**
     * Returns the actual config
     * - cardsFileName: string; filename of the source image containing images of the cards
     * - dogsPlayingPokerFileName: string; filename of the image of dogs playing poker
     * - cardSource: array; describes how cards (indexed 0 to 51, alphabetically sorted by suit and numerically sorted
     *   by rank) are positioned in the source image
     *    - width: int; width of the card in pixels
     *    - height: int; height of the card in pixels
     *    - x: int; X location of the card in the cards image
     *    - y: int; Y location of the card in the cards image
     * - cardDestination: array; describes how cards are to be transformed and positioned in the Dogs Playing Poker
     *   image
     *     - x: int; X destination of the card
     *     - y: int; Y destination of the card
     *     - scale: float; ratio for resizing the card from the original image (optional)
     *     - flip: boolean; whether or not to flip the card upside down (defaults to TRUE)
     *     - rotation: float; degrees by which to rotate the image (optional)
     *     - distortion: array; relative control points for applying a perspective distortion to the image (optional)
     *         - topLeft: array; configuration for distorting the top left corner of the image
     *            - x: int; number of pixels to move the top left corner (positive = right; negative = left)
     *            - y: int; number of pixels to move the top left corner (positive = down; negative = up)
     *         - topRight: array; configuration for distorting the top right corner of the image
     *            - x: int; number of pixels to move the top right corner (positive = right; negative = right)
     *            - y: int; number of pixels to move the top right corner (positive = down; negative = up)
     *         - bottomRight: array; configuration for distorting the bottom right corner of the image
     *            - x: int; number of pixels to move the bottom right corner (positive = right; negative = right)
     *            - y: int; number of pixels to move the bottom right corner (positive = down; negative = up)
     *         - bottomLeft: array; configuration for distorting the bottom left corner of the image
     *            - x: int; number of pixels to move the bottom left corner (positive = left; negative = left)
     *            - y: int; number of pixels to move the bottom left corner (positive = down; negative = up)
     *     - mask: array; polygonal area of the card to make transparent in the Dogs Playing Poker image. List of X and
     *       Y values (optional)
     *
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'cardsFileName' => __DIR__ . '/../../resources/cards.png',
            'dogsPlayingPokerFileName' => __DIR__ . '/../../resources/dogs-playing-poker.png',
            'cardSource' => [
                0 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 0,
                    'y' => 210
                ],
                1 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 50,
                    'y' => 210
                ],
                2 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 100,
                    'y' => 210
                ],
                3 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 150,
                    'y' => 210
                ],
                4 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 200,
                    'y' => 210
                ],
                5 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 250,
                    'y' => 210
                ],
                6 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 300,
                    'y' => 210
                ],
                7 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 350,
                    'y' => 210
                ],
                8 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 400,
                    'y' => 210
                ],
                9 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 450,
                    'y' => 210
                ],
                10 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 500,
                    'y' => 210
                ],
                11 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 550,
                    'y' => 210
                ],
                12 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 600,
                    'y' => 140
                ],
                13 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 0,
                    'y' => 140
                ],
                14 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 50,
                    'y' => 140
                ],
                15 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 100,
                    'y' => 140
                ],
                16 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 150,
                    'y' => 140
                ],
                17 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 200,
                    'y' => 140
                ],
                18 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 250,
                    'y' => 140
                ],
                19 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 300,
                    'y' => 140
                ],
                20 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 350,
                    'y' => 140
                ],
                21 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 400,
                    'y' => 140
                ],
                22 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 450,
                    'y' => 140
                ],
                23 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 500,
                    'y' => 140
                ],
                24 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 550,
                    'y' => 140
                ],
                25 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 600,
                    'y' => 140
                ],
                26 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 0,
                    'y' => 70
                ],
                27 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 50,
                    'y' => 70
                ],
                28 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 100,
                    'y' => 70
                ],
                29 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 150,
                    'y' => 70
                ],
                30 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 200,
                    'y' => 70
                ],
                31 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 250,
                    'y' => 70
                ],
                32 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 300,
                    'y' => 70
                ],
                33 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 350,
                    'y' => 70
                ],
                34 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 400,
                    'y' => 70
                ],
                35 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 450,
                    'y' => 70
                ],
                36 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 500,
                    'y' => 70
                ],
                37 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 550,
                    'y' => 70
                ],
                38 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 600,
                    'y' => 70
                ],
                39 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 0,
                    'y' => 0
                ],
                40 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 50,
                    'y' => 0
                ],
                41 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 100,
                    'y' => 0
                ],
                42 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 150,
                    'y' => 0
                ],
                43 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 200,
                    'y' => 0
                ],
                44 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 250,
                    'y' => 0
                ],
                45 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 300,
                    'y' => 0
                ],
                46 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 350,
                    'y' => 0
                ],
                47 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 400,
                    'y' => 0
                ],
                48 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 450,
                    'y' => 0
                ],
                49 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 500,
                    'y' => 0
                ],
                50 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 550,
                    'y' => 0
                ],
                51 => [
                    'width' => 50,
                    'height' => 70,
                    'x' => 600,
                    'y' => 0
                ]
            ],
            'cardDestination' => [
                0 => [
                    'x' => 272,
                    'y' => 333,
                    'scale' => .25,
                    'flip' => true,
                    'distortion' => [
                        'topLeft' => ['x' => -10, 'y' => 35],
                        'topRight' => ['x' => 5, 'y' => 10],
                        'bottomRight' => ['x' => 140, 'y' => -20],
                        'bottomLeft' => ['x' => 80, 'y' => 10]
                    ]
                ],
                1 => [
                    'x' => 300,
                    'y' => 328,
                    'scale' => .25,
                    'flip' => true,
                    'distortion' => [
                        'topLeft' => ['x' => -10, 'y' => 35],
                        'topRight' => ['x' => 5, 'y' => 10],
                        'bottomRight' => ['x' => 140, 'y' => -20],
                        'bottomLeft' => ['x' => 80, 'y' => 10]
                    ]
                ],
                2 => [
                    'x' => 320,
                    'y' => 332,
                    'scale' => .25,
                    'flip' => true,
                    'distortion' => [
                        'topLeft' => ['x' => -10, 'y' => 35],
                        'topRight' => ['x' => 10, 'y' => 15],
                        'bottomRight' => ['x' => 140, 'y' => -20],
                        'bottomLeft' => ['x' => 85, 'y' => 10]
                    ]
                ],
                3 => [
                    'x' => 385,
                    'y' => 325,
                    'scale' => .4,
                    'flip' => true,
                    'distortion' => [
                        'topLeft' => ['x' => 0, 'y' => 30],
                        'topRight' => ['x' => -10, 'y' => 30],
                        'bottomRight' => ['x' => 0, 'y' => -5],
                        'bottomLeft' => ['x' => -10, 'y' => 5]
                    ]
                ],
                4 => [
                    'x' => 405,
                    'y' => 325,
                    'scale' => .4,
                    'flip' => true,
                    'distortion' => [
                        'topLeft' => ['x' => 0, 'y' => 30],
                        'topRight' => ['x' => -10, 'y' => 30],
                        'bottomRight' => ['x' => 10, 'y' => 10],
                        'bottomLeft' => ['x' => -10, 'y' => 10]
                    ]
                ],
                5 => [
                    'x' => 420,
                    'y' => 320,
                    'scale' => .4,
                    'flip' => true,
                    'distortion' => [
                        'topLeft' => ['x' => 0, 'y' => 30],
                        'topRight' => ['x' => -10, 'y' => 30],
                        'bottomRight' => ['x' => 20, 'y' => 10],
                        'bottomLeft' => ['x' => -10, 'y' => 10]
                    ]
                ],
                6 => [
                    'x' => 442,
                    'y' => 318,
                    'scale' => .375,
                    'flip' => true,
                    'distortion' => [
                        'topLeft' => ['x' => -10, 'y' => 30],
                        'topRight' => ['x' => -10, 'y' => 20],
                        'bottomRight' => ['x' => 35, 'y' => 10],
                        'bottomLeft' => ['x' => 0, 'y' => 10]
                    ]
                ],
                7 => [
                    'x' => 523,
                    'y' => 338,
                    'scale' => .33,
                    'rotation' => -110.0,
                    'distortion' => [
                        'topLeft' => ['x' => 20, 'y' => -30],
                        'topRight' => ['x' => 20, 'y' => -20],
                        'bottomRight' => ['x' => 10, 'y' => 30],
                        'bottomLeft' => ['x' => 10, 'y' => 50]
                    ]
                ],
                8 => [
                    'x' => 500,
                    'y' => 329,
                    'scale' => .30,
                    'rotation' => -105.0,
                    'distortion' => [
                        'topLeft' => ['x' => 20, 'y' => -30],
                        'topRight' => ['x' => 20, 'y' => -20],
                        'bottomRight' => ['x' => 10, 'y' => 30],
                        'bottomLeft' => ['x' => 10, 'y' => 40]
                    ],
                    'mask' => [
                        ['x' => 30, 'y' => 50],
                        ['x' => 30, 'y' => 70],
                        ['x' => 50, 'y' => 70],
                        ['x' => 30, 'y' => 50]
                    ]
                ],
                9 => [
                    'x' => 544,
                    'y' => 342,
                    'scale' => .35,
                    'rotation' => -108.0,
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
                ],
                10 => [
                    'x' => 550,
                    'y' => 362,
                    'scale' => 0.40,
                    'rotation' => -50.0,
                    'distortion' => [
                        'topLeft' => ['x' => 0, 'y' => -15],
                        'topRight' => ['x' => 20, 'y' => 10],
                        'bottomRight' => ['x' => 70, 'y' => 90],
                        'bottomLeft' => ['x' => 30, 'y' => 40]
                    ]
                ],
                11 => [
                    'x' => 575,
                    'y' => 360,
                    'scale' => 0.40,
                    'rotation' => -60.0,
                    'distortion' => [
                        'topLeft' => ['x' => 0, 'y' => -15],
                        'topRight' => ['x' => 20, 'y' => 10],
                        'bottomRight' => ['x' => 60, 'y' => 70],
                        'bottomLeft' => ['x' => 30, 'y' => 40]
                    ]
                ]
            ]
        ];
    }
}
