<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\framework\Component\Image\Bridge\Image;

use app\framework\Component\Image\Bridge\Image\Palette\Color\ColorInterface;

/**
 * Abstract font base class
 */
abstract class AbstractFont implements FontInterface
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var integer
     */
    protected $size;

    /**
     * @var ColorInterface
     */
    protected $color;

    /**
     * Constructs a font with specified $file, $size and $color
     *
     * The font size is to be specified in points (e.g. 10pt means 10)
     *
     * @param string         $file
     * @param integer        $size
     * @param ColorInterface $color
     */
    public function __construct($file, $size, ColorInterface $color)
    {
        $this->file  = $file;
        $this->size  = $size;
        $this->color = $color;
    }

    /**
     * {@inheritdoc}
     */
    final public function getFile()
    {
        return $this->file;
    }

    /**
     * {@inheritdoc}
     */
    final public function getSize()
    {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    final public function getColor()
    {
        return $this->color;
    }
}
