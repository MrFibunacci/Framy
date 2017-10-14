<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\EventManager;


    use app\framework\Component\StdLib\Exception\AbstractException;

    class EventManagerException extends AbstractException
    {
        const INVALID_PRIORITY_VALUE = 101;
        const INVALID_EVENT_HANDLER = 102;
        const INVALID_EVENT_NAME = 103;

        protected static $messages = [
            101 => 'Event listener priority must be greater than 100 and smaller than 1000.',
            102 => 'Event handler must be a valid callable, class name or class instance.',
            103 => 'Event name must be a string at least 1 character long.'
        ];
    }