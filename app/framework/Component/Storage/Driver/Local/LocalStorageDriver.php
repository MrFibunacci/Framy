<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 */

    namespace app\framework\Component\Storage\Driver\Local;

    use app\framework\Component\StdLib\StdObject\ArrayObject\ArrayObject;
    use app\framework\Component\Storage\StorageException;
    use app\framework\Component\Storage\Driver\SizeAwareInterface;
    use app\framework\Component\Storage\Driver\DriverInterface;
    use app\framework\Component\Storage\Driver\DirectoryAwareInterface;
    use app\framework\Component\Storage\Driver\AbsolutePathInterface;
    use app\framework\Component\Storage\Driver\TouchableInterface;

    class LocalStorageDriver implements SizeAwareInterface,DriverInterface,AbsolutePathInterface,TouchableInterface,DirectoryAwareInterface
    {
        protected $directory;
        protected $recentKey = null;
        protected $publicUrl;

        function __construct($config)
        {
            if(is_array($config)){
                $config = new ArrayObject($config);
            }

            if(!$config instanceof ArrayObject){
                throw new StorageException('Storage driver config must be an array or ArrayObject!');
            }

            $this->helper = LocalHelper::getInstance();
            $this->directory = $this->helper->normalizeDirectoryPath($config->key("root", '', true));
        }

        public function keyExists($key)
        {
            $this->recentKey = $key;

            return file_exists($this->buildPath($key));
        }

        private function buildPath($key)
        {
            $path = $this->helper->buildPath($key, $this->directory, $this->create);
            if (strpos($path, $this->directory) !== 0) {
                throw new StorageException(StorageException::PATH_IS_OUT_OF_STORAGE_ROOT, [
                    $path,
                    $this->directory
                ]);
            }

            return $path;
        }

        /**
         * Get file size
         *
         * @param $key
         *
         * @return int
         */
        public function getSize($key)
        {
            $this->recentKey = $key;

            if($this->keyExists($key)){
                return filesize($this->buildPath($key));
            }

            return false;
        }

        /**
         * Reads the contents of the file
         *
         * @param string $key
         *
         * @return string|boolean if cannot read content
         */
        public function getContents($key)
        {
            $this->recentKey = $key;

            if($this->keyExists($key)){
                return file_get_contents($this->buildPath($key));
            }

            return false;
        }

        /**
         * Writes the given File
         *
         * @param      $key
         * @param      $contents
         *
         * @param bool $append
         *
         * @return bool|int The number of bytes that were written into the file
         */
        public function setContents($key, $contents, $append = false)
        {
            $this->recentKey = $key;

            if($this->keyExists($key)){
                return file_put_contents($this->buildPath($key), $contents);
            }

            return false;
        }

        /**
         * Returns an array of all keys (files and directories)
         *
         * For storages that do not support directories, both parameters are irrelevant.
         *
         * @param string   $key       (Optional) Key of a directory to get keys from. If not set - keys will be read from the storage root.
         *
         * @param bool|int $recursive (Optional) Read all items recursively. Pass integer value to specify recursion depth.
         *
         * @return array
         */
        public function getKeys($key = '', $recursive = false)
        {
            if ($key != '') {
                $key = ltrim($key, DS);
                $key = rtrim($key, DS);
                $path = $this->directory . DS . $key;
            } else {
                $path = $this->directory;
            }

            if (!is_dir($path)) {
                return [];
            }

            if ($recursive) {
                try {
                    $config = \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS;
                    $directoryIterator = new \RecursiveDirectoryIterator($path, $config);
                    $iterator = new \RecursiveIteratorIterator($directoryIterator);
                    if (is_int($recursive) && $recursive > -1) {
                        $iterator->setMaxDepth($recursive);
                    }
                } catch (\Exception $e) {
                    $iterator = new \EmptyIterator;
                }
                $files = iterator_to_array($iterator);
            } else {
                $files = [];
                $iterator = new \DirectoryIterator($path);
                foreach ($iterator as $fileinfo) {
                    $name = $fileinfo->getFilename();
                    if ($name == '.' || $name == '..') {
                        continue;
                    }
                    $files[] = $fileinfo->getPathname();
                }
            }

            $keys = [];


            foreach ($files as $file) {
                $keys[] = $this->helper->getKey($file, $this->directory);
            }
            sort($keys);


            return $keys;
        }

        /**
         * Returns the last modified time
         *
         * @param string $key
         *
         * @return integer|boolean A UNIX like timestamp or false
         */
        public function getTimeModified($key)
        {
            $this->recentKey = $key;

            if($this->keyExists($key)){
                return filectime($this->buildPath($key));
//                return fileatime($this->buildPath($key));
//                return filemtime($this->buildPath($key));
            }

            return false;
        }

        /**
         * Deletes the file
         *
         * @param string $key
         *
         * @return boolean
         */
        public function deleteKey($key)
        {
            $this->recentKey = $key;
            $path = $this->buildPath($key);

            if ($this->isDirectory($key)) {
                return @rmdir($path);
            }

            return @unlink($path);
        }

        /**
         * Renames a file
         *
         * @param string $sourceKey
         * @param string $targetKey
         *
         * @return bool
         * @throws \app\framework\Component\Storage\StorageException
         */
        public function renameKey($sourceKey, $targetKey)
        {
            $this->recentKey = $sourceKey;

            if($this->keyExists($sourceKey)){
                $targetPath = $this->buildPath($targetKey);
                $this->helper->ensureDirectoryExists(dirname($targetPath), true);

                return rename($this->buildPath($sourceKey), $targetPath);
            }
            throw new StorageException(StorageException::FILE_NOT_FOUND);
        }

        /**
         * Returns most recent file key that was used by a storage
         *
         * @return string|null
         */
        public function getRecentKey()
        {
            return $this->recentKey;
        }

        /**
         * Returns public file URL
         *
         * @param $key
         *
         * @return mixed
         */
        public function getURL($key)
        {
            $key = str_replace('\\', '/', $key);

            return $this->publicUrl . '/' . ltrim($key, "/");
        }

        /**
         * Does this storage create a date folder structure?
         *
         * @return boolean
         */
        public function createDateFolderStructure()
        {
            // TODO: Implement createDateFolderStructure() method.
        }

        /**
         * @param $key
         *
         * @return mixed
         */
        public function getAbsolutePath($key)
        {
            $this->recentKey = $key;

            return $this->buildPath($key);
        }

        /**
         * Touch a file (change time modified)
         *
         * @param $key
         *
         * @return mixed
         */
        public function touchKey($key)
        {
            $this->recentKey = $key;

            return touch($this->buildPath($key));
        }

        /**
         * Check if key is directory
         *
         * @param string $key
         *
         * @return boolean
         */
        public function isDirectory($key)
        {
            // TODO: Implement isDirectory() method.
        }
    }