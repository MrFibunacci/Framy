<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\EventManager;

    use app\framework\Component\StdLib\StdLibTrait;
    use app\framework\Component\StdLib\StdObject\StdObjectWrapper;

    class EventListener
    {
        use StdLibTrait;

        private $handler = null;
        private $method = 'handle';
        private $priority = 101;

        /**
         * @return null
         */
        public function getHandler()
        {
            return $this->handler;
        }

        /**
         * @return string
         */
        public function getMethod()
        {
            return $this->method;
        }

        /**
         * @return int
         */
        public function getPriority()
        {
            return $this->priority;
        }

        /**
         * Set handler for event. Can be a callable, class name or class instance.
         *
         * @param $handler
         *
         * @throws EventManagerException
         * @return $this
         */
        public function handler($handler)
        {
            // check if is valid handler
            if($this->isNumber($handler) || $this->isBoolean($handler) || $this->isEmpty($handler)){
                throw new EventManagerException(EventManagerException::INVALID_EVENT_HANDLER);
            }

            if($this->isString($handler) || $this->isStringObject($handler)){
                $handler = StdObjectWrapper::toString($handler);
                if(!class_exists($handler)){
                    throw new EventManagerException(EventManagerException::INVALID_EVENT_HANDLER);
                }
                $handler = new $handler;
            }
            $this->handler = $handler;

            return $this;
        }

        /**
         * Set listener priority between 101 and 999.<br />
         * Default priority is 101.<br />
         * Bigger values mean higher listener priority, and will be executed first.
         *
         * @param $priority
         *
         * @return $this
         * @throws EventManagerException
         */
        public function priority($priority)
        {
            if (!$this->isNumber($priority)) {
                throw new EventManagerException(EventManagerException::MSG_INVALID_ARG, [
                        '$priority',
                        'integer'
                    ]
                );
            }

            if ($priority <= 100 || $priority >= 1000) {
                throw new EventManagerException(EventManagerException::INVALID_PRIORITY_VALUE);
            }
            $this->priority = $priority;

            return $this;
        }

        /**
         * Set method to be called on handler.<br />
         * If not set, default method will be called:
         * <code>handle(Event $event)</code>
         *
         * @param string $method Method to call on handler
         *
         * @throws EventManagerException
         * @return $this
         */
        public function method($method)
        {
            if (!$this->isString($method) && !$this->isStringObject($method)) {
                throw new EventManagerException(EventManagerException::MSG_INVALID_ARG, [
                        '$method',
                        'string|StringObject'
                    ]
                );
            }
            $this->method = StdObjectWrapper::toString($method);

            return $this;
        }
    }