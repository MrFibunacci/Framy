<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib\StdObject;

    use app\framework\Component\StdLib\StdObject\ArrayObject\ArrayObject;

    trait StdObjectTrait {
        protected static function arr(array $array)
        {
            return new ArrayObject($array);
        }
    }