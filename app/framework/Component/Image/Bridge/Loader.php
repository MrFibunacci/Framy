<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 */


    namespace app\framework\Component\Image\Bridge;

    use app\framework\Component\Image\Bridge\Imagine\Imagine;
    use app\framework\Component\Image\Image;
    use app\framework\Component\Image\ImageException;
    use app\framework\Component\StdLib\StdLibTrait;


    /**
     * Image bridge loader.
     *
     * @package app\framework\Component\Image\Bridge
     */
    class Loader
    {
        use StdLibTrait

        /**
         * @var string Default Image bridge.
         */
        private static $library = '\app\framework\Component\Image\Bridge\Imagine\Imagine';

        /**
         * Returns an instance of ImageLoaderInterface based on current bridge.
         *
         *
         */
        public static function getImageLoader($config)
        {
            $lib = self::getLibrary();

            /** @var ImageLoaderInterface $libInstance */
            $instance = self::factory($lib, '\app\framework\Component\Image\Bridge\ImageLoaderInterface', [$config]);

            if (!self::isInstanceOf($instance, '\app\framework\Component\Image\Bridge\ImageLoaderInterface')) {
                throw new ImageException('The message library must implement "\app\framework\Component\Image\Bridge\ImageLoaderInterface".'
                );
            }

            return $instance;
        }


        /**
         * Get the name of bridge library which will be used as the driver.
         *
         * @return string
         */
        private static function getLibrary()
        {
            return Image::getConfig()->get('Bridge', self::$library);
        }

        /**
         * Change the default library used for the driver.
         *
         * @param string $pathToClass Path to the new driver class. Must be an instance of \app\framework\Component\Image\Bridge\ImageLoaderInterface
         */
        public static function setLibrary($pathToClass)
        {
            self::$library = $pathToClass;
        }
    }