<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Stopwatch;

    use app\framework\Component\StdLib\StdLibTrait;

    /**
     * Stopwatch section.
     *
     * @package app\framework\Component\Stopwatch
     */
    class Section
    {
        use StdLibTrait;

        /**
         * @var StopwatchEvent[]
         */
        private $events = array();

        /**
         * @var null|float
         */
        private $origin;

        /**
         * @var string
         */
        private $id;

        /**
         * @var Section[]
         */
        private $children = array();

        /**
         * Constructor.
         *
         * @param float|null $origin Set the origin of the events in this section, use null to set their origin to their start time
         */
        public function __construct($origin = null)
        {
            $this->origin = $this->isNumber($origin) ? $origin : null;
        }

        /**
         * Returns the child section.
         *
         * @param string $id The child section identifier
         *
         * @return self|null The child section or null when none found
         */
        public function get($id)
        {
            foreach ($this->children as $child) {
                if ($id === $child->getId()) {
                    return $child;
                }
            }
        }

        /**
         * Creates or re-opens a child section.
         *
         * @param string|null $id null to create a new section, the identifier to re-open an existing one
         *
         * @return self
         */
        public function open($id)
        {
            if (null === $session = $this->get($id)) {
                $session = $this->children[] = new self(microtime(true) * 1000);
            }
            return $session;
        }
        /**
         * @return string The identifier of the section
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Sets the session identifier.
         *
         * @param string $id The session identifier
         *
         * @return $this
         */
        public function setId($id)
        {
            $this->id = $id;
            return $this;
        }

        /**
         * Starts an event.
         *
         * @param string $name     The event name
         * @param string $category The event category
         *
         * @return StopwatchEvent The event
         */
        public function startEvent($name, $category)
        {
            if (!$this->is($this->events[$name])) {
                $this->events[$name] = new StopwatchEvent($this->origin ?: microtime(true) * 1000, $category);
            }
            return $this->events[$name]->start();
        }

        /**
         * Checks if the event was started.
         *
         * @param string $name The event name
         *
         * @return bool
         */
        public function isEventStarted($name)
        {
            return isset($this->events[$name]) && $this->events[$name]->isStarted();
        }

        /**
         * Stops an event.
         *
         * @param string $name The event name
         *
         * @return StopwatchEvent The event
         *
         * @throws \LogicException When the event has not been started
         */
        public function stopEvent($name)
        {
            if (!$this->is($this->events[$name])) {
                throw new \LogicException(sprintf('Event "%s" is not started.', $name));
            }
            return $this->events[$name]->stop();
        }

        /**
         * Stops then restarts an event.
         *
         * @param string $name The event name
         *
         * @return StopwatchEvent The event
         *
         * @throws \LogicException When the event has not been started
         */
        public function lap($name)
        {
            return $this->stopEvent($name)->start();
        }

        /**
         * Returns a specific event by name.
         *
         * @param string $name The event name
         *
         * @return StopwatchEvent The event
         *
         * @throws \LogicException When the event is not known
         */
        public function getEvent($name)
        {
            if (!$this->is($this->events[$name])) {
                throw new \LogicException(sprintf('Event "%s" is not known.', $name));
            }
            return $this->events[$name];
        }

        /**
         * Returns the events from this section.
         *
         * @return StopwatchEvent[] An array of StopwatchEvent instances
         */
        public function getEvents()
        {
            return $this->events;
        }
    }