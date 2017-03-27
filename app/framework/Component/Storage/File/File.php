<?php
    namespace app\framework\Component\Storage\File;

    use app\framework\Component\Storage\Storage;
    use app\framework\Component\Storage\StorageException;

    class File implements FileInterface
    {
        protected $storage;
        protected $key;

        function __construct($key, Storage $storage)
        {
            $this->storage = $storage;
            $this->key     = $key;

            //make sure a file path is given
            if(!$this->storage->keyExists($this->key)){
                throw new StorageException(StorageException::FILE_NOT_FOUND, [$key]);
            }
        }

        /**
         * Get file storage
         *
         * @return Storage
         */
        public function getStorage()
        {
            return $this->storage;
        }

        /**
         * Get file key
         *
         * @return string
         */
        public function getKey()
        {
            return $this->key;
        }

        /**
         * Get time modified
         *
         * @param bool $asDateTimeObject
         *
         * @return int|DateTimeObject UNIX timestamp or DateTimeObject
         */
        public function getTimeModified($asDateTimeObject = false)
        {
            // TODO: Implement getTimeModified() method.
        }

        /**
         * Set file contents (writes contents to storage)<br />
         *
         * Fires an event StorageEvent::FILE_SAVED after the file content was written.
         *
         * @param mixed $contents
         *
         * @param bool  $append
         *
         * @return $this
         */
        public function setContents($contents, $append = false)
        {
            return $this->storage->setContents($this->key, $contents, $append);
        }

        /**
         * Get file size in bytes
         *
         * @return int|null Number of bytes or null
         */
        public function getSize()
        {
            return $this->storage->getSize($this->key);
        }

        /**
         * Touch a file (change time modified)
         *
         * @return $this
         */
        public function touch()
        {
            return $this->storage->touchKey($this->key);
        }

        /**
         * @return mixed
         */
        public function getContent()
        {
            return $this->storage->getContents($this->key);
        }

        /**
         * Cpt. Obviously approves that this method renames the File.
         *
         * @param $newName
         * @return mixed
         */
        public function rename($newName)
        {
            return $this->storage->renameKey($this->key, $newName);
        }

        /**
         * Deletes the File.
         *
         * @return mixed
         */
        public function delete()
        {
            return $this->storage->deleteKey($this->key);
        }

        /**
         * @return mixed
         */
        public function getAbsolutePath()
        {
            return $this->storage->getAbsolutePath($this->key);
        }


    }