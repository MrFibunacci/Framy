<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 */

    namespace app\framework\Component\Image\Bridge\Imagine;


    class Imagine
    {
        private $instance;

        /**
         * Base constructor
         */
        function __construct($setting)
        {
            $library = $setting['Library'];
            $this->instance = $this->getLibraryInstance($library);
        }

        /**
         * @return \app\framework\Component\Image\Bridge\Gd\Imagine|\app\framework\Component\Image\Bridge\Gmagick\Imagine|\app\framework\Component\Image\Bridge\Imagick\Imagine
         */
        public function getInstance()
        {
            return $this->instance;
        }

        private function getLibraryInstance($library)
        {
            switch($library){
                case "gd":
                    return new \app\framework\Component\Image\Bridge\Gd\Imagine();
                    break;
                case "imagick":
                    return new \app\framework\Component\Image\Bridge\Imagick\Imagine();
                    break;
                case "gmagick":
                    return new \app\framework\Component\Image\Bridge\Gmagick\Imagine();
                    break;
                default:
                    throw new ImagineException('Unsupported image library "' . $library . '". Cannot create Imagine instance.'
                    );
                    break;
            }
        }

    }