<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Storage\File;

    use app\framework\Component\StdLib\StdObject\DateTimeObject\DateTimeObject;
    use app\framework\Component\Storage\Storage;

    interface FileInterface {

        /**
         * Get file storage
         *
         * @return Storage
         */
        public function getStorage();

        /**
         * Get file key
         *
         * @return string
         */
        public function getKey();

        /**
         * Get time modified
         *
         * @param bool $asDateTimeObject
         *
         * @return int|DateTimeObject UNIX timestamp or DateTimeObject
         */
        public function getTimeModified($asDateTimeObject = false);

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
        public function setContents($contents, $append = false);

        /**
         * Get file size in bytes
         *
         * @return int|null Number of bytes or null
         */
        public function getSize();

        /**
         * Touch a file (change time modified)
         *
         * @return $this
         */
        public function touch();

        /**
         * Get file contents
         *
         * @return mixed
         */
        public function getContent();

        /**
         * Cpt. Obviously approves that this method renames the File.
         *
         * @param $newName
         * @return mixed
         */
        public function rename($newName);

        /**
         * Deletes the File.
         *
         * @return mixed
         */
        public function delete();

        /**
         * @return mixed
         */
        public function getAbsolutePath();
    }