<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\View;

    use app\framework\Component\TemplateEngine\TemplateEngine;

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
        private $templateEngine;

        function __construct($view = null, $data = [], $path, $engine = null)
        {
            $this->view   = $view;
            $this->path   = $path;
            $this->data   = $data;
            $this->engine = $engine;

            $TE = new TemplateEngine();
            $this->templateEngine = $TE->getInstance();
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
            // TODO write some awesome shit that renders the template now
            $this->templateEngine->display($this->view);
        }
    }
