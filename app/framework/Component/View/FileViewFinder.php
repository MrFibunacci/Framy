<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\View;

    use app\framework\Component\StdLib\ValidatorTrait;
    use app\framework\Component\Storage\Storage;

    class FileViewFinder implements ViewFinderInterface
    {
        use ValidatorTrait;

        /**
         * The Storage instance.
         *
         * @var \app\framework\Component\Storage\Storage
         */
        protected $files;

        /**
         * The array of active view paths.
         *
         * @var array
         */
        protected $paths = [];

        /**
         * The array of views that have been located.
         *
         * @var array
         */
        protected $views = [];

        /**
         * The namespace to file path hints.
         *
         * @var array
         */
        protected $hints = [];

        /**
         * Register a view extension with the finder.
         *
         * @var array
         */
        protected $extensions = ['php', 'tpl'];

        /**
         * Create a new file view loader instance.
         */
        function __construct(Storage $files, array $extensions = null)
        {
            $this->files = $files;
            $this->paths[] = $files->getDisk()->getPath();

            if($this->is($extensions)){
                $this->extensions = $this->fixExtensionsArray($extensions);
            }
        }

        /**
         * Get the fully qualified location of the view.
         *
         * @param  string $name
         *
         * @return string
         */
        public function find($name)
        {
            if ($this->is($this->views[$name])) {
                return $this->views[$name];
            }

            if ($this->hasHintInformation($name = trim($name))) {
                return $this->views[$name] = $this->findNamedPathView($name);
            }

            return $this->views[$name] = $this->findInPaths($name, $this->paths);
        }

        /**
         * Add a location to the finder.
         *
         * @param  string $location
         *
         * @return void
         */
        public function addLocation($location)
        {
            $this->paths[] = $location;
        }

        /**
         * Add a namespace hint to the finder.
         *
         * @param  string       $namespace
         * @param  string|array $hints
         *
         * @return void
         */
        public function addNamespace($namespace, $hints)
        {
            $hints = (array) $hints;

            if ($this->is($this->hints[$namespace])) {
                $hints = array_merge($this->hints[$namespace], $hints);
            }

            $this->hints[$namespace] = $hints;
        }

        /**
         * Prepend a namespace hint to the finder.
         *
         * @param  string       $namespace
         * @param  string|array $hints
         *
         * @return void
         */
        public function prependNamespace($namespace, $hints)
        {
            $hints = (array) $hints;

            if ($this->is($this->hints[$namespace])) {
                $hints = array_merge($hints, $this->hints[$namespace]);
            }

            $this->hints[$namespace] = $hints;
        }

        /**
         * Add a valid view extension to the finder.
         *
         * @param  string $extension
         *
         * @return void
         */
        public function addExtension($extension)
        {
            if (($index = array_search($extension, $this->extensions)) !== false) {
                unset($this->extensions[$index]);
            }

            array_unshift($this->extensions, $extension);
        }

        /**
         * Returns whether or not the view specify a hint information.
         *
         * @param  string  $name
         * @return bool
         */
        public function hasHintInformation($name)
        {
            return strpos($name, static::HINT_PATH_DELIMITER) > 0;
        }

        /**
         * Get the segments of a template with a named path.
         *
         * @param  string  $name
         * @return array
         *
         * @throws \InvalidArgumentException
         */
        protected function getNamespaceSegments($name)
        {
            $segments = explode(static::HINT_PATH_DELIMITER, $name);

            if (count($segments) != 2) {
                throw new \InvalidArgumentException("View [$name] has an invalid name.");
            }

            if (! isset($this->hints[$segments[0]])) {
                throw new \InvalidArgumentException("No hint path defined for [{$segments[0]}].");
            }

            return $segments;
        }

        /**
         * Get an array of possible view files.
         *
         * @param  string  $name
         * @return array
         */
        protected function getPossibleViewFiles($name)
        {
            return array_map(function ($extension) use ($name) {
                return str_replace('.', '/', $name).'.'.$extension;
            }, $this->extensions);
        }

        /**
         * Get the path to a template with a named path.
         *
         * @param  string  $name
         * @return string
         */
        protected function findNamedPathView($name)
        {
            list($namespace, $view) = $this->getNamespaceSegments($name);

            return $this->findInPaths($view, $this->hints[$namespace]);
        }

        /**
         * Find the given view in the list of paths.
         *
         * @param  string  $name
         * @param  array   $paths
         * @return string
         *
         * @throws \InvalidArgumentException
         */
        protected function findInPaths($name, $paths)
        {
            foreach ((array) $paths as $path) {
                foreach ($this->getPossibleViewFiles($name) as $file) {
                    return $path.'/'.$file;
                }
            }

            throw new \InvalidArgumentException("View [$name] not found.");
        }
        private function fixExtensionsArray($extensions)

        {
            $temp = [];
            foreach($extensions as $key => $val){
                if(substr($key, 0, 1 ) === "."){
                    $temp[] = ltrim($key, '.');
                } else {
                    $temp[] = $key;
                }
            }
            return $temp;
        }
    }