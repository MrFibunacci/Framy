<?php
    if(! function_exists("dd")){
        /**
         * Little helper called dump and die
         * @param $val
         */
        function dd($val)  {
            var_dump($val);die;
        }
    }

    if(! function_exists("view")){
        /**
         * Get the evaluated view contents for the given view.
         *
         * @param  string  $view        Name of template file.
         * @param  array   $data        Data to set values in template file
         * @param  array   $mergeData   Some shit I don't know yet
         * @return \app\framework\Component\View\View
         */
        function view($view = null, $data = [], $mergeData = []){
            $View = new \app\framework\Component\View\View($view, $data);
            $View->render();
        }
    }

    if(! function_exists("app")){
        /**
         * Used to easily call Methods from classes without manually set
         * locally Instances of them.
         *
         * @param string $classMethod The class name(if in \app\custom\ namespace) or the "namespace+className@methodToCall"
         */
        function app($classMethod){
            return $GLOBALS["App"]->call($classMethod);
        }
    }

    if(! function_exists("url")){
        /**
         * Basically completes just the the url
         * e.g. /test to yourexample.site/test
         *
         * Simple, very simple.
         *
         * @param $path
         *
         * @return string
         */
        function url($path){
            return $_SERVER['HTTP_HOST'].$path;
        }
    }