<?php
namespace app\framework\Component\Image\Bridge\Draw;

use app\framework\Component\Image\Bridge\Image\FontInterface;
use app\framework\Component\Image\Bridge\Image\PointCollection;
use app\framework\Component\Image\Bridge\Image\PointInterface;

interface PlotterInterface
{
    /**
     * @param DrawerInterface|null $drawer
     */
    public function __construct(DrawerInterface $drawer = null);

    /**
     * @param DrawerInterface $drawer
     */
    public function setDrawer(DrawerInterface $drawer);

    /**
     * @param PointInterface $point1
     * @param PointInterface $point2
     * @param LineStyle|null $style
     */
    public function plot(PointInterface $point1, PointInterface $point2, LineStyle $style = null);

    /**
     * @param PointCollection $collection
     * @param LineStyle|null $style
     */
    public function plotCollection(PointCollection $collection, LineStyle $style = null);

    /**
     * @param PointInterface $point
     * @param $text
     * @param FontInterface $font
     */
    public function label(PointInterface $point, $text, FontInterface $font);
}
