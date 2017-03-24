<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Form\Fields;


    interface FieldInterface
    {
        public static function setParameter($name, $type, $placeholder, $id, $value, $content, $for);
        public static function parseHtml($fieldData);
    }