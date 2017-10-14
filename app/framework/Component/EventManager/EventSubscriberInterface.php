<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\EventManager;

    /**
     * This interface is used for event subscriber classes
     *
     * @package app\framework\Component\EventManager
     */
    interface EventSubscriberInterface
    {
        /**
         * Subscribe to events
         * @return void
         */
        public function subscribe();
    }