<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Validation;


    trait ValidationTrait
    {
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
