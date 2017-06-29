<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
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
          * @param string $storageName Storage name
          *
          * @throws \app\framework\Component\Storage\Storage
          * @return Storage
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
