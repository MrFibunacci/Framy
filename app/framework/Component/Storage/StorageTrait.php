<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

     namespace app\framework\Component\Storage;

     /**
      * A library of Storage functions
      *
      * @package app\framework\Component\Storage
      */
     trait StorageTrait
     {
         /**
          * Get storage
          *
          * @param $storageName
          * @return Storage
          * @throws StorageException
          */
         protected static function storage($storageName)
         {
             try {
                 return new Storage($storageName);
             } catch(StorageException $e) {
                 throw $e;
             }
         }
     }
