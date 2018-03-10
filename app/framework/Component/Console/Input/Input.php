<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Input;

    /**
     * Input is the base class for all concrete Input classes.
     *
     * @package app\framework\Component\Console\Input
     */
    abstract class Input
    {
        protected $definition;
        protected $options = array();
        protected $arguments = array();
        protected $interactive = true;

        public function __construct($definition = null)
        {

        }
    }