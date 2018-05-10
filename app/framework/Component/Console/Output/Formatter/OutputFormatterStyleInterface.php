<?php
/**
 *
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Output\Formatter;


    /**
     * Formatter style interface for defining styles.
     *
     * @package app\framework\Component\Console\Output\Formatter
     */
    interface OutputFormatterStyleInterface
    {
        /**
         * Sets style foreground color.
         *
         * @param string $color The color name
         */
        public function setForeground($color = null);

        /**
         * Sets style background color.
         *
         * @param string $color The color name
         */
        public function setBackground($color = null);

        /**
         * Sets some specific style option.
         *
         * @param string $option The option name
         */
        public function setOption($option);

        /**
         * Unsets some specific style option.
         *
         * @param string $option The option name
         */
        public function unsetOption($option);

        /**
         * Sets multiple style options at once.
         */
        public function setOptions(array $options);

        /**
         * Applies the style to a given text.
         *
         * @param string $text The text to style
         *
         * @return string
         */
        public function apply($text);
    }