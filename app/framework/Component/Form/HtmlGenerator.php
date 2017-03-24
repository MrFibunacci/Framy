<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Form;

    use app\framework\Component\Form\Fields\Input;
    use app\framework\Component\Form\Fields\Label;
    use app\framework\Component\Form\Fields\Span;

    class HtmlGenerator
    {
        public static function generate($fields)
        {
            $html = "";
            foreach($fields as $field){
                switch($field['fieldType']){
                    case "input":
                        $html .= Input::parseHtml($field);
                        break;
                    case "label":
                        $html .= Label::parseHtml($field);
                        break;
                    case "group":
                        $html .= "<div class='form-group ".$field['state']."'>";
                        $html .= $field["content"]->generateHtml();
                        $html .= "</div>";
                        break;
                    case "span":
                        $html .= Span::parseHtml($field);
                        break;
                }
            }
            return $html."</form>";
        }
    }