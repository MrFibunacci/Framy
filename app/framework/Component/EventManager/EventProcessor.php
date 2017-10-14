<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\EventManager;

    use app\framework\Component\StdLib\SingletonTrait;
    use app\framework\Component\StdLib\StdLibTrait;
    use app\framework\Component\StdLib\StdObject\ArrayObject\ArrayObject;

    /**
     * EventProcessor is a class that takes EventListeners and Event object and processes the event.
     *
     * @package app\framework\Component\EventManager
     */
    class EventProcessor
    {
        use StdLibTrait, SingletonTrait;

        /**
         * Process given event
         *
         * @param array|ArrayObject $eventListeners EventListeners that are subscribed to this event
         * @param Event             $event Event data object
         *
         * @param null|string       $resultType Type of event result to enforce (can be any class or interface name)
         * @param null|int          $limit Number of results to return
         *
         * @return array
         */
        public function process($eventListeners, Event $event, $resultType = null, $limit = null){
            $eventListeners = $this->orderByPriority($eventListeners);

            $results = [];

            /* @var $eventListener EventListener */
            foreach($eventListeners as $eventListener){
                $handler = $eventListener->getHandler();
                if($this->isNull($handler)){
                    continue;
                }

                $method = $eventListener->getMethod();

                if($this->isCallable($handler)){
                    /* @var $handler \Closure */
                    $result = $handler($event);
                } else {
                    $result = call_user_func([
                        $handler,
                        $method,

                    ], [$event]);
                }

                if($this->isNull($resultType) || (!$this->isNull($resultType) && $this->isInstanceOf($result, $resultType))){
                    $results[] = $result;

                    if($limit === 1 && count($results) === 1){
                        return $results[0];
                    }

                    if(count($results) === $limit){
                        return $results;
                    }
                }

                if($event->isPropagationStopped()){
                    break;
                }

            }

            return $results;
        }

        /**
         * @param $eventListeners
         *
         * @return mixed
         */
        private function orderByPriority($eventListeners)
        {
            /**
             * @param int $a
             * @param int $b
             *
             * @return int
             */
            $comparisonFunction = function ($a, $b) {
                if ($a->getPriority() == $b->getPriority()) {
                    // This will keep the order of same priority listeners in the order of subscribing
                    return 1;
                }

                return ($a->getPriority() > $b->getPriority()) ? -1 : 1;
            };

            return $this->arr($eventListeners)->sortUsingFunction($comparisonFunction)->val();
        }
    }