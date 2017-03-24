<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Form;

    use app\framework\Component\Form\Layouts\Group;
    use app\framework\Component\StdLib\StdLibTrait;

    class Form
    {
        use StdLibTrait,FormControl;

        private $options;

        private $fields = array();

        private $addToGroup = null;


        function __construct($options = ["class" => "form-vertical", "action" => null, "method" => "GET"])
        {
            $this->options = $options;
        }

        public function add($fieldType, $name = null, $type = null, $placeholder = null, $id = null, $value = null, $content = null, $for = null)
        {
            if($this->isNull($this->addToGroup)){
                $this->fieldType($fieldType,$name, $type, $placeholder, $id, $value, $content, $for);
            } else {
                foreach($this->fields as $field){
                    if($field['fieldType'] == "group" && $field['name'] == $this->addToGroup){
                        $field['content']->add($fieldType, $name, $type, $placeholder, $id, $value, $content, $for);
                    }
                }

                $this->addToGroup = null;
            }
        }

        public function addToGroup($name)
        {
            $this->addToGroup = $name;

            return $this;
        }

        public function addGroup($name, $state = null)
        {
            $this->fields[] = [
                "fieldType" => "group",
                "name" => $name,
                "state" => $state,
                "content" => new Group()
            ];
        }

        public function generateHtml()
        {
            $html = "<form class='".$this->options["class"]."' action='".$this->options["action"]."' method='".$this->options["method"]."'>";
            $html .= '<input type="hidden" name="csrf" value="'.$_SESSION['csrf_token'].'">';

            $html .= HtmlGenerator::generate($this->fields);

            return $html."</form>";
        }
    }