<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib\StdObject\UrlObject;

    use app\framework\Component\StdLib\StdObject\StdObjectException;

    /**
     * UrlObject exception class.
     *
     * @package         Webiny\Component\StdLib\StdObject\UrlObject
     */
    class UrlObjectException extends StdObjectException
    {
        const MSG_INVALID_URL = 101;

        protected static $messages = [
            101 => 'Unable to parse "%s" as a valid url.'
        ];
    }
