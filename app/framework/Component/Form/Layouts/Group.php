<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Form\Layouts;

    use app\framework\Component\Form\FormControl;
    use app\framework\Component\Form\HtmlGenerator;

    class Group
    {
        use FormControl;

        private $fields;

        public function add($fieldType,$name, $type, $placeholder, $id, $value, $content, $for)
        {
            $this->fieldType($fieldType,$name, $type, $placeholder, $id, $value, $content, $for);
        }

        public function generateHtml()
        {
            $result = "";
            $result .= HtmlGenerator::generate($this->fields);
            return $result;
        }
    }