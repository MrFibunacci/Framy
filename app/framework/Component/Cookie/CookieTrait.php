<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Cookie;

    use app\framework\Component\Cookie\Cookie;

    trait CookieTrait
    {
        protected function createCookie($cookieName, $cookieValue = null, $expire = null, $path = "/", $encrypt = false)
        {
            if($this->cookieJar[$cookieName] == null) {
                $this->cookieJar[$cookieName] = new Cookie($cookieName, $cookieValue, $expire, $path, $encrypt);
                return true;
            } else {
                return false;
            }
        }
    }