<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 */

    namespace app\framework\Component\Image;

    use app\framework\Component\Image\Bridge\Loader;
    use app\framework\Component\Storage\File\File;

    /**
     * Use this class to create an Image instance.
     * You can load images using these methods:<br>
     *  - `open` => opens an image from disk by providing an instance of \app\framework\Component\Storage\File\File<br>
     *  - `load` => creates an image from given binary string<br>
     *  - `create` => creates a blank image<br>
     *  - `resource` => create an image from the given resource, e.g. from upload stream<br>
     *
     * @package         app\framework\Component\Image
     */
    class ImageLoader implements ImageLoaderInterface
    {
        private $loaderInstance = null;

        private function getLoaderInstance()
        {
            if(is_null($this->loaderInstance)){
                $this->loaderInstance = new Loader();
            }

            return $this->loaderInstance;
        }

        /**
         * Creates a new ImageInterface instance from the given image at the provided path.
         *
         * @param File $image Path to an image on the disk.
         *
         * @return ImageInterface
         */
        public static function open(File $image)
        {
            // TODO: Implement open() method.

//            self::getLoaderInstance()->;
        }

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
        public function create($width, $height, $bgColor = null)
        {
            // TODO: Implement create() method.
        }

        /**
         * Create a new ImageInterface instance form the given binary string.
         *
         * @param string $string Binary string that holds image information.
         *
         * @return mixed
         */
        public function load($string)
        {
            // TODO: Implement load() method.
        }

        /**
         * Create a new ImageInterface instance from the given resource.
         *
         * @param mixed $resource Resource.
         *
         * @return ImageInterface
         */
        public function resource($resource)
        {
            // TODO: Implement resource() method.
        }
    }