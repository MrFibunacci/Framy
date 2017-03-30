<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\View;

    use app\framework\Component\Storage\Storage;

    /**
     * Class Factory to kind of prepare all values for the actual View Class.
     *
     * @package app\framework\Component\View
     */
    class Factory
    {
        /**
         * The view finder implementation.
         *
         * @var \app\framework\Component\View\ViewFinderInterface
         */
        protected $finder;

        /**
         * Array of registered view name aliases.
         *
         * @var array
         */
        protected $aliases = [];

        /**
         * All of the registered view names.
         *
         * @var array
         */
        protected $names = [];

        /**
         * The extension to engine bindings.
         *
         * @var array
         */
        protected $extensions = ['.tpl' => 'Smarty', '.mustache' => 'mustache', '.php' => 'php'];

        function __construct()
        {
            $this->finder = new FileViewFinder(new Storage("templates"), $this->extensions);
        }
    }