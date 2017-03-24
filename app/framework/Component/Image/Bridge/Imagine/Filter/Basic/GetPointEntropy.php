<?php

namespace app\framework\Component\Image\Bridge\Filter\Basic;

use app\framework\Component\Image\Bridge\Filter\FilterInterface;
use app\framework\Component\Image\Bridge\Image\BoxInterface;
use app\framework\Component\Image\Bridge\Image\ImageInterface;

class GetPointEntropy implements FilterInterface
{
    /**
     * @var BoxInterface
     */
    private $size;

    /**
     * Constructs a GetPointEntropy filter
     *
     * @param BoxInterface $size
     */
    public function __construct(BoxInterface $size)
    {
        $this->size = $size;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(ImageInterface $image)
    {
        return $image->getPointEntropy($this->size);
    }
}
