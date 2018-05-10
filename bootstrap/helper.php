<?php
    if(! function_exists("dd")) {
        /**
         * Little helper called dump and die
         * @param $val
         */
        function dd($val) {
            var_dump($val);die;
        }
    }

    if(! function_exists("view")) {
        /**
         * Get the evaluated view contents for the given view.
         *
         * @param  string  $view        Name of template file.
         * @param  array   $data        Data to set values in template file
         * @param  array   $mergeData   Some shit I don't know yet
         * @return \app\framework\Component\View\View
         */
        function view($view = null, $data = [], $mergeData = []) {
            $View = new \app\framework\Component\View\View($view, $data);
            return $View->render();
        }
    }

    if(! function_exists("app")) {
        /**
         * Used to easily call Methods from classes without manually set
         * locally Instances of them.
         *
         * @param string $classMethod The class name(if in \app\custom\ namespace) or the "namespace+className@methodToCall"
         */
        function app($classMethod, $param = null) {
            return $GLOBALS["App"]->call($classMethod, $param);
        }
    }

    if(! function_exists("url")) {
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
        function url($path) {
            return $_SERVER['HTTP_HOST'].$path;
        }
    }

    if(! function_exists("pathTo")) {
        /**
         * Easy function to get the path to the project + if you want an directory in it.
         *
         * @param null $path
         * @return bool|string
         */
        function pathTo($path = null) {
            return realpath(dirname(__FILE__)."/../".$path);
        }
    }

    if(! function_exists("getStringBetween")) {
        /**
         * This is a handy little function to strip out a string between
         * two specified pieces of text. This could be used to parse
         * XML text, bbCode, or any other delimited code/text for that matter.
         *
         * @param $string
         * @param $start
         * @param $end
         * @return bool|string
         */
        function getStringBetween($string, $start, $end) {
            $string = ' ' . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return '';
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }
    }