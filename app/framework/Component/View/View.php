<?php
    /**
     * Framey Framework
     *
     * @copyright Copyright Framey
     * @Author Marco Bier <mrfibunacci@gmail.com>
     */

    namespace app\framework\Component\View;

    use app\framework\Component\TemplateEngine\TemplateEngine;
    use app\framework\Component\Storage\Storage;

    class View
    {
        /**
         * The name of the view.
         *
         * @var string
         */
        protected $view;

        /**
         * The array of view data.
         *
         * @var array
         */
        protected $data;

        /**
         * The path to the view file.
         *
         * @var string
         */
        protected $path;

        /**
         * An funcking instance of the goddam templateEngine
         *
         * @var
         */
        private $TemplateEngine;

        function __construct($view = null, $data = [])
        {
            $this->view   = self::validateViewName($view);
            $this->path   = $path;
            $this->data   = $data;
            $this->engine = $engine;

            $TE = new TemplateEngine();
            $Storage = new Storage("templates");

            $this->TemplateEngine = $TE->getInstance();
            $this->TemplateEngine->setTemplateDir($Storage->getAbsolutePath());
            $this->TemplateEngine->setCompileDir($Storage->getAbsolutePath()."/templates_c");
        }

        /**
         * Get the string contents of the view.
         *
         * @param  callable|null  $callback
         * @return string
         *
         * @throws \Throwable
         */
        public function render(callable $callback = null)
        {
            try {
                $this->assignData($this->data);
                $this->TemplateEngine->display($this->view);
            } catch(\Exception $e) {
                echo $e->getMessage();
            }
        }

        private function assignData($data)
        {
            foreach ($data as $valueName => $dataSet) {
                $this->TemplateEngine->assign($valueName, $dataSet);
            }
        }

        private static function validateViewName($view)
        {
            if(explode(".", $view)[1] == null){
                return $view.".tpl";
            } else {
                return $view;
            }
        }
    }
