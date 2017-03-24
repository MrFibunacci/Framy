<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 */

    namespace app\framework\Component\Image;

    use app\framework\Component\Storage\File\File;

    interface ImageLoaderInterface {

        /**
         * Create a blank image with of given dimensions and fill it with $bgColor.
         *
         * @param int    $width       Width of the new image.
         * @param int    $height      Height of the new image.
         * @param string $bgColor     Background color. Following formats are acceptable
         *                            - "fff"
         *                            - "ffffff"
         *                            - array(255,255,255)
         *
         * @return ImageInterface
         */
        public function create($width, $height, $bgColor = null);

        /**
         * Creates a new ImageInterface instance from the given image at the provided path.
         *
         * @param File $image Path to an image on the disk.
         *
         * @return ImageInterface
         */
        public static function open(File $image);

        /**
         * Create a new ImageInterface instance form the given binary string.
         *
         * @param string $string Binary string that holds image information.
         *
         * @return mixed
         */
        public function load($string);

        /**
         * Create a new ImageInterface instance from the given resource.
         *
         * @param mixed $resource Resource.
         *
         * @return ImageInterface
         */
        public function resource($resource);
    }