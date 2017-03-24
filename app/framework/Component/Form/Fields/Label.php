<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Form\Fields;


    class Label implements FieldInterface
    {
        public static function setParameter($name = null, $type = null, $placeholder = null, $id = null, $value = null, $content = null, $for = null)
        {
            return [
                "fieldType" => "label",
                "content" => $content,
                "for" => $for
            ];
        }

        public static function parseHtml($fieldData)
        {
            return "<label class='control-label' for='".$fieldData['for']."'>".$fieldData['content']."</label>";
        }
    }