<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\framework\Component\Image\Bridge\Filter\Advanced;

use app\framework\Component\Image\Bridge\Filter\FilterInterface;
use app\framework\Component\Image\Bridge\Image\ImageInterface;
use app\framework\Component\Image\Bridge\Image\Point;

/**
 * The Grayscale filter calculates the gray-value based on RGB.
 */
class Grayscale extends OnPixelBased implements FilterInterface
{
    public function __construct()
    {
        parent::__construct(function (ImageInterface $image, Point $point) {
            $color = $image->getColorAt($point);
            $image->draw()->dot($point, $color->grayscale());
        });
    }
}
