<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 */

    namespace app\framework\Component\Image;

    use app\framework\Component\Storage\File;

    /**
     * ImageInterface must be implemented by each image bridge library.
     *
     * @package app\framework\components\Image
     */
    interface ImageInterface {

        /**
         * Returns the width and height of the image in pixels.
         *
         * @return Array
         */
        public function getSize();

        /**
         * Get the image mime-type format.
         * Can be [jpg, jpeg, png, gif].
         *
         * @return String
         */
        public function getFormat();

        /**
         * Sets image mime-type format.
         *
         * @param string $format Format name. Supported formats are [jpg, jpeg, png, gif]
         *
         * @throws ImageException
         */
        public function setFormat($format);

        /**
         * @param int  $width                   Width of new Image
         * @param int  $height                  Height of new Image
         * @param bool $preserveAspectRatio     Do you wish to preserve the aspect ration while resizing. Default is true.
         *                                      NOTE: If you preserve the aspect ratio, the output image might not match the
         *                                      defined width and height.
         * @return mixed
         */
        public function resize($width, $height, $preserveAspectRatio = true);
    }