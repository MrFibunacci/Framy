<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\View;

    use app\framework\Component\Config\Config;
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
            $this->data   = $data;

            $TE = new TemplateEngine();
            $this->TemplateEngine = $TE->getInstance();

            // set Config path
            $this->TemplateEngine->setCompileDir($this->getPathFromConfig(Config::getInstance()->get("compiled", "view"))[0]->getAbsolutePath());

            // get view paths
            $config = Config::getInstance()->get("paths", "view");
            $View = $this->getPathFromConfig($config);

            // add view paths
            $i = 0;
            foreach($View as $key => $value){
                if($key === "path"){
                    if($i <= 0)
                        $this->TemplateEngine->setTemplateDir($value);
                    else
                        $this->TemplateEngine->addTemplateDir($value);
                } else {
                    if($i <= 0)
                        $this->TemplateEngine->setTemplateDir($value->getAbsolutePath());
                    else
                        $this->TemplateEngine->addTemplateDir($value->getAbsolutePath());
                }
                $i++;
            }
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

                $expView = explode(":", $this->view);
                if($expView[1] == null) {
                    $this->TemplateEngine->display($this->view);
                } elseif($expView[0] == "fetch") {
                    return $this->TemplateEngine->fetch(self::validateViewName($expView[1]));
                }
            } catch(\Exception $e) {
                echo $e->getMessage();
            }
        }

        private function assignData($data)
        {
            foreach ($data as $valueName => $dataSet) {
                if(explode(":", $dataSet)[1] != null){
                    $viewName = explode(":", $dataSet)[1];

                    switch (explode(":", $dataSet)[0]) {
                        case "fetch":
                                $this->TemplateEngine->assign($valueName, $this->TemplateEngine->fetch(self::validateViewName($viewName)));
                            break;
                        default:
                            $this->TemplateEngine->assign($valueName, $dataSet);
                            break;
                    }

                } else {

                    $this->TemplateEngine->assign($valueName, $dataSet);
                }
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

        private function getPathFromConfig($config)
        {
            if(!is_array($config))
                $config = array($config);

            $View = false;
            foreach($config as $path){
                $path = explode(":", $path);
                if($path[0] == "disk"){
                    $View[] = new Storage($path[1]);
                } else {
                    $View["paths"] = $path;
                }
            }
            return $View;
        }
    }
