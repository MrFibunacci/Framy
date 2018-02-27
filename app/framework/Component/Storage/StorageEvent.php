<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Storage;

    use app\framework\Component\EventManager\Event;
    use app\framework\Component\Storage\File\File;

    /**
     * Class StorageEvent
     *
     * @package app\framework\Component\Storage
     */
    class StorageEvent extends Event
    {
        /**
         * This event is fired after a file was renamed, it also sets a property `oldKey` into the event object.<br />
         * You can use it if you need to know what file was renamed.
         */
        const FILE_RENAMED = "ff.storage.file_renamed";
        /**
         * This event is fired after the file content was written to storage.
         */
        const FILE_SAVED = "ff.storage.file_saved";
        /**
         * This event is fired after the file was deleted from the storage.
         */
        const FILE_DELETED = "ff.storage.file_deleted";

        /**
         * @var File
         */
        protected $file;

        /**
         * @var Storage
         */
        protected $storage;

        public function __construct(File $file)
        {
            $this->file = $file;
            $this->storage = $file->getStorage();
            parent::__construct();
        }

        /**
         * Get working file
         * @return File
         */
        public function getFile()
        {
            return $this->file;
        }

        /**
         * @return Storage
         */
        public function getStorage()
        {
            return $this->storage;
        }
    }