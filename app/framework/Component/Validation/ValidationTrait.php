<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 */

    namespace app\framework\Component\Validation;


    trait ValidationTrait {
        /**
         * Get Validation component
         *
         * @return Validation
         */
        protected static function validate()
        {
            return Validation::getInstance();
        }
    }