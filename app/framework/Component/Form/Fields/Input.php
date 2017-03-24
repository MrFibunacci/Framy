<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Form\Fields;


    use app\framework\Component\StdLib\StdLibTrait;

    class Input implements FieldInterface
    {
        use StdLibTrait;

        public static function setParameter($name = null, $type = null, $placeholder = null, $id = null, $value = null, $content = null, $for = null)
        {
            return [
                "fieldType" => "input",
                "name" => $name,
                "type" => $type,
                "placeholder" => $placeholder,
                "id" => $id,
                "value" => $value,
            ];
        }

        public static function parseHtml($fieldData)
        {
            $result = "<input class='form-control' name='".$fieldData["name"]."' type='".$fieldData['type']."' ";
            if(self::is($fieldData['placeholder'])){
                $result .= "placeholder='".$fieldData["placeholder"]."'";
            } elseif(self::is($fieldData['id'])){
                $result .= "id='".$fieldData["id"]."'";
            } elseif(self::is($fieldData['value'])){
                $result .= "value='".$fieldData["value"]."'";
            }
            return $result."/>";
        }
    }