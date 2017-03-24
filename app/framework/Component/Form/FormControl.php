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

    trait FormControl
    {
        public function fieldType($fieldType,$name = null,$type = null, $placeholder = null, $id = null, $value = null, $content = null, $for = null)
        {
            switch($fieldType){
                case "input":
                    $this->input($name, $type, $placeholder, $id, $value);
                    break;
                case "label":
                    $this->label($content, $for);
                    break;
                case "span":
                    $this->span($content);
                    break;
            }
        }

        private function input($name, $type, $placeholder, $id, $value)
        {
            $this->fields[] = Input::setParameter($name, $type, $placeholder, $id, $value);
        }

        private function label($content, $for)
        {
            $this->fields[] = Label::setParameter(null, null, null, null, null, $content, $for);
        }

        private function span($content)
        {
            $this->fields[] = Span::setParameter(null, null, null, null, null, $content, null);
        }
    }