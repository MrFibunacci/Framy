<?php
/**
 *
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Output\Formatter;


    class OutputFormatterStyleStack
    {
        /**
         * @var OutputFormatterStyleInterface[]
         */
        private $styles;
        private $emptyStyle;

        public function __construct(OutputFormatterStyleInterface $emptyStyle = null)
        {
            $this->emptyStyle = $emptyStyle ?: new OutputFormatterStyle();
            $this->reset();
        }

        /**
         * Resets stack (ie. empty internal arrays).
         */
        public function reset()
        {
            $this->styles = array();
        }

        /**
         * Pushes a style in the stack.
         */
        public function push(OutputFormatterStyleInterface $style)
        {
            $this->styles[] = $style;
        }

        /**
         * Pops a style from the stack.
         *
         * @return OutputFormatterStyleInterface
         *
         * @throws \InvalidArgumentException When style tags incorrectly nested
         */
        public function pop(OutputFormatterStyleInterface $style = null)
        {
            if (empty($this->styles)) {
                return $this->emptyStyle;
            }
            if (null === $style) {
                return array_pop($this->styles);
            }
            foreach (array_reverse($this->styles, true) as $index => $stackedStyle) {
                if ($style->apply('') === $stackedStyle->apply('')) {
                    $this->styles = array_slice($this->styles, 0, $index);
                    return $stackedStyle;
                }
            }
            throw new \InvalidArgumentException('Incorrectly nested style tag found.');
        }

        /**
         * Computes current style with stacks top codes.
         *
         * @return OutputFormatterStyle
         */
        public function getCurrent()
        {
            if (empty($this->styles)) {
                return $this->emptyStyle;
            }
            return $this->styles[count($this->styles) - 1];
        }

        /**
         * @return $this
         */
        public function setEmptyStyle(OutputFormatterStyleInterface $emptyStyle)
        {
            $this->emptyStyle = $emptyStyle;

            return $this;
        }

        /**
         * @return OutputFormatterStyleInterface
         */
        public function getEmptyStyle()
        {
            return $this->emptyStyle;
        }
    }