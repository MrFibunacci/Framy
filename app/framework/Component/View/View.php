<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\View;


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

        function __construct($view = null, $data = [], $path, $engine)
        {
            $this->view = $view;
            $this->path = $path;
            $this->engine = $engine;
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
        {}
    }