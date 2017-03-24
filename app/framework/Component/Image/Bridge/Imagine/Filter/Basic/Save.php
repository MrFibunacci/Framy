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
 * A save filter
 */
class Save implements FilterInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $options;

    /**
     * Constructs Save filter with given path and options
     *
     * @param string $path
     * @param array  $options
     */
    public function __construct($path = null, array $options = array())
    {
        $this->path    = $path;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(ImageInterface $image)
    {
        return $image->save($this->path, $this->options);
    }
}
