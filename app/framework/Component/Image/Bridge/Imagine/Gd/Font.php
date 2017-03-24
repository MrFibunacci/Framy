<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\framework\Component\Image\Bridge\Gd;

use app\framework\Component\Image\Bridge\Exception\RuntimeException;
use app\framework\Component\Image\Bridge\Image\AbstractFont;
use app\framework\Component\Image\Bridge\Image\Box;

/**
 * Font implementation using the GD library
 */
final class Font extends AbstractFont
{
    /**
     * {@inheritdoc}
     */
    public function box($string, $angle = 0)
    {
        if (!function_exists('imageftbbox')) {
            throw new RuntimeException('GD must have been compiled with `--with-freetype-dir` option to use the Font feature.');
        }

        $angle    = -1 * $angle;
        $info     = imageftbbox($this->size, $angle, $this->file, $string);
        $xs       = array($info[0], $info[2], $info[4], $info[6]);
        $ys       = array($info[1], $info[3], $info[5], $info[7]);
        $width    = abs(max($xs) - min($xs));
        $height   = abs(max($ys) - min($ys));

        return new Box($width, $height);
    }
}
