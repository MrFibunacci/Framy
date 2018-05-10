<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Storage\File;

    use app\framework\Component\EventManager\EventManagerTrait;
    use app\framework\Component\StdLib\StdObject\DateTimeObject\DateTimeObject;
    use app\framework\Component\Storage\Storage;
    use app\framework\Component\Storage\StorageEvent;
    use app\framework\Component\Storage\StorageException;

    class File implements FileInterface
    {
        use EventManagerTrait;

        protected $storage;
        protected $key;
        protected $timeModified;

        /**
         * Construct a File instance
         *
         * @param string  $key     File key
         * @param Storage $storage Storage to use
         *
         * @throws \app\framework\Component\Storage\StorageException
         */
        function __construct($key, Storage $storage)
        {
            $this->storage = $storage;
            $this->key     = $key;

            //make sure a file path is given
            if($this->storage->keyExists($this->key) && $this->getStorage()->isDirectory($this->key)){
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
            if ($this->timeModified === null) {
                $this->timeModified = $time = $this->storage->getTimeModified($this->key);
                if ($time) {
                    $this->timeModified = $asDateTimeObject ? $this->datetime()->setTimestamp($time) : $time;
                }
            }

            return $this->timeModified;
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
         * @return bool | int
         */
        public function setContents($contents, $append = false)
        {
            $this->eventManager()->fire(StorageEvent::FILE_SAVED, new StorageEvent($this));
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
            if ($this->storage->renameKey($this->key, $newName)) {
                $event = new StorageEvent($this);

                $event->oldName = $this->key;
                $this->key = $newName;
                $this->eventManager()->fire(StorageEvent::FILE_RENAMED, $event);
                return true;
            }

            return false;
        }

        /**
         * Deletes the File.
         *
         * @return mixed
         */
        public function delete()
        {
            if ($this->storage->deleteKey($this->key)) {
                $this->eventManager()->fire(StorageEvent::FILE_DELETED, new StorageEvent($this));

                return true;
            }

            return false;
        }

        /**
         * @return mixed
         */
        public function getAbsolutePath()
        {
            return $this->storage->getAbsolutePath($this->key);
        }
    }