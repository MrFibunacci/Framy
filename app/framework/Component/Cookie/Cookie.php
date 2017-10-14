<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Cookie;
    use app\framework\Component\StdLib\StdLibTrait;

    /**
     * Class Cookie
     * @package app\framework\Component\Cookie
     */
    class Cookie
    {
        use StdLibTrait;

        private $name;

        private $value;

        private $expire;

        private $path;

        private $encrypt;

        /**
         * Cookie constructor.
         *
         * @param        $cookieName
         * @param null   $cookieValue
         * @param null   $expire
         * @param string $path
         * @param bool   $encrypt
         */
        public function __construct($cookieName, $cookieValue = null, $expire = null, $path = "/", $encrypt = false)
        {
            // if no value specified get existing cookie else create new cookie
            if($this->isNull($cookieValue)) {
                $this->getFromExistingCookie($cookieName);
            } else {
                // set values and create new
                $this->name    = $cookieName;
                $this->value   = $cookieValue;
                $this->expire  = $expire;
                $this->path    = $path;
                $this->encrypt = $encrypt;

                $this->update();
            }
        }

        /**
         * @return null
         */
        public function getValue()
        {
            $this->isSerialized($this->value, $this->value);

            return ($this->value);
        }

        /**
         * @param null $value
         */
        public function setValue($value)
        {
            $this->value = $value;
            $this->update();
        }

        /**
         * @return mixed
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param mixed $name
         */
        public function setName($name)
        {
            $this->name = $name;
            $this->update();
        }

        /**
         * @return null
         */
        public function getExpire()
        {
            return $this->expire;
        }

        /**
         * @param null $expire
         */
        public function setExpire($expire)
        {
            $this->expire = $expire;
            $this->update();
        }

        private function getFromExistingCookie(&$cookieName)
        {
            if(!$this->isNull($_COOKIE[$cookieName])) {
                $this->name  = $cookieName;
                $this->value = $_COOKIE[$cookieName];
                $this->validate();
            }
        }

        private function update()
        {
            $this->validate();
            setcookie($this->name, $this->value, $this->expire, $this->path);
        }

        private function validate(){
            if($this->isSerialized($this->value, $this->value))
                return true;

            switch (gettype($this->value)) {
                case "object":
                case "array":
                    $this->value = serialize($this->value); // $this->serialize($this->value); TODO: if Issue #14 is fiexed then use this method
                    break;
            }
        }
    }