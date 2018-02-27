<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Storage\Driver\Local;

    use app\framework\Component\StdLib\SingletonTrait;
    use app\framework\Component\Storage\StorageException;

    /**
     * Class LocalHelper v1.1
     *
     * @package app\framework\Component\Storage\Driver\Local
     */
    class LocalHelper
    {
        use SingletonTrait;

        /**
         * Gets the key using file $path and storage $directory
         *
         * @param string $path      Path to extract file key from
         *
         * @param string $directory Directory of the storage
         *
         * @return string
         */
        public function getKey($path, $directory)
        {
            $path = $this->normalizePath($path);

            return ltrim(substr($path, strlen($directory)), '/');
        }

        /**
         * Create directory
         *
         * @param string $directory Directory path to create
         *
         * @throws \app\framework\Component\Storage\StorageException
         */
        protected function createDirectory($directory)
        {
            $umask = umask(0);
            $created = mkdir($directory, 0777, true);
            umask($umask);

            if (!$created) {
                throw new StorageException(StorageException::DIRECTORY_COULD_NOT_BE_CREATED, [$directory]);
            }
        }

        /**
         * To check if directory exists.
         *
         * @param $directory
         * @param $create
         * @throws StorageException If directory doesn't exist
         */
        public function ensureDirectoryExists($directory, $create)
        {
            if (!is_dir($directory)) {
                if (!$create) {
                    throw new StorageException(StorageException::DIRECTORY_DOES_NOT_EXIST, [$directory]);
                }
                $this->createDirectory($directory);
            }
        }

        /**
         * Normalize path (strip '.', '..' and make sure it's not a symlink)
         *
         * @param $path
         *
         * @return string
         */
        public function normalizeDirectoryPath($path)
        {
            $path = $this->normalizePath($path);

            if (is_link($path)) {
                $path = realpath($path);
            }

            return $path;
        }

        /**
         * Normalizes the given path
         *
         * @param string $path
         *
         * @return string
         */
        protected function normalizePath($path)
        {
            $path = str_replace('\\', '/', $path);
            $prefix = $this->getAbsolutePrefix($path);
            $path = substr($path, strlen($prefix));
            $parts = array_filter(explode('/', $path), 'strlen');
            $tokens = array();

            foreach ($parts as $part) {
                switch ($part) {
                    case '.':
                        continue;
                        break;
                    case '..':
                        if (count($tokens) !== 0) {
                            array_pop($tokens);
                            continue;
                        } elseif (!empty($prefix)) {
                            continue;
                        }
                        break;
                    default:
                        $tokens[] = $part;
                }
            }

            return $prefix . implode('/', $tokens);
        }

        /**
         * Returns the absolute prefix of the given path
         *
         * @param string $path A normalized path
         *
         * @return string
         */
        protected function getAbsolutePrefix($path)
        {
            preg_match('|^(?P<prefix>([a-zA-Z]:)?/)|', $path, $matches);

            if (empty($matches['prefix'])) {
                return '';
            }

            return strtolower($matches['prefix']);
        }

        /**
         * Build absolute path by given $key and $directory
         *
         * @param $key
         * @param $directory
         * @param $create
         *
         * @return mixed
         */
        public function buildPath($key, $directory, $create)
        {
            $this->ensureDirectoryExists($directory, $create);

            return $this->normalizeDirectoryPath($directory . '/' . $key);
        }
    }