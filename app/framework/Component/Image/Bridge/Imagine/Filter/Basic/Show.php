<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\framework\Component\Image\Bridge\Filter\Basic;

use app\framework\Component\Image\Bridge\Image\ImageInterface;
use app\framework\Component\Image\Bridge\Filter\FilterInterface;

/**
 * A show filter
 */
class Show implements FilterInterface
{
    /**
     * @var string
     */
    private $format;

    /**
     * @var array
     */
    private $options;

    /**
     * Constructs the Show filter with given format and options
     *
     * @param string $format
     * @param array  $options
     */
    public function __construct($format, array $options = array())
    {
        $this->format  = $format;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(ImageInterface $image)
    {
        return $image->show($this->format, $this->options);
    }
}
