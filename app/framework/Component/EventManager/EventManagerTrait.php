<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\EventManager;


    /**
     * A library of EventManager functions
     *
     * @package app\framework\Component\EventManager
     */
    trait EventManagerTrait
    {
        /**
         * Get event Manager
         * @return EventManager
         */
        protected static function eventManager()
        {
            return EventManager::getInstance();
        }
    }