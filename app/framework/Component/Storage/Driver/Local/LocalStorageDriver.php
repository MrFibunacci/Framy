<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Storage\Driver\Local;

    use app\framework\Component\StdLib\StdObject\ArrayObject\ArrayObject;
    use app\framework\Component\Storage\StorageException;
    use app\framework\Component\Storage\Driver\SizeAwareInterface;
    use app\framework\Component\Storage\Driver\DriverInterface;
    use app\framework\Component\Storage\Driver\DirectoryAwareInterface;
    use app\framework\Component\Storage\Driver\AbsolutePathInterface;
    use app\framework\Component\Storage\Driver\TouchableInterface;

    /**
     * Class LocalStorageDriver v1.0
     *
     * @package app\framework\Component\Storage\Driver\Local
     */
    class LocalStorageDriver implements SizeAwareInterface,DriverInterface,AbsolutePathInterface,TouchableInterface,DirectoryAwareInterface
    {
        /**
         * @var string
         */
        protected $directory;

        /**
         * the last interacted key
         *
         * @var null
         */
        protected $recentKey = null;

        /**
         * Public url
         *
         * @var
         */
        protected $publicUrl;

        /**
         * Contains the LocalHelper Class
         *
         * @var LocalHelper
         */
        private $helper;

        /**
         * LocalStorageDriver constructor.
         *
         * @param $config
         * @throws StorageException
         */
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

        /**
         * Check if file/key exists
         *
         * @param string $key
         * @return bool
         */
        public function keyExists($key)
        {
            $this->recentKey = $key;

            return file_exists($this->buildPath($key));
        }

        /**
         * Build path or throw exception
         *
         * @param $key
         * @return mixed
         * @throws StorageException
         */
        private function buildPath($key)
        {
            //$path = $this->helper->buildPath($key, $this->directory, $this->create);
            $path = $this->helper->buildPath($key, $this->directory, true);
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

            if($this->keyExists($key)) {
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
         * @return true if succeeds
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
         * TODO: fix missing dateFolderStructure variable
         *
         * @return boolean
         */
        public function createDateFolderStructure()
        {
            return $this->dateFolderStructure;
        }

        /**
         * To get the absolute path
         *
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
            $this->recentKey = $key;
            $path = $this->buildPath($key);

            if ($this->isDirectory($key)) {
                return @rmdir($path);
            }

            return @unlink($path);
        }
    }