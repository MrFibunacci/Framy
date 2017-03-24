<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Form\Fields;


    class Span implements FieldInterface
    {
        public static function setParameter($name, $type, $placeholder, $id, $value, $content, $for)
        {
            return [
                "fieldType" => "span",
                "content" => $content
            ];
        }

        public static function parseHtml($fieldData)
        {
            return "<span class='".$fieldData['content']." form-control-feedback'></span>";
        }

    }