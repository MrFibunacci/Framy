<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib\StdObject\DateTimeObject;

    use app\framework\Component\StdLib\StdObject\AbstractStdObject;
    use app\framework\Component\StdLib\StdObject\ArrayObject\ArrayObject;
    use \app\framework\Component\Config\Config;
    use app\framework\Component\StdLib\StdObject\StringObject\StringObject;

    class DateTimeObject extends AbstractStdObject
    {
        use ValidatorTrait, ManipulatorTrait;

        /**
         * @var null|\DateTime
         */
        protected $value = null;

        /**
         * This is the default timezone. It's set to the servers timezone.
         * This object is static because we don't want to detect the default timezone each time.
         * @var null|\DateTimeZone
         */
        private static $defaultTimezone = null;

        /**
         * Default date format
         * @var string
         */
        private static $defaultFormat = 'Y-m-d H:i:s';

        /**
         * @var Timezone of entry date timestamp.
         */
        private $timezone = null;

        /**
         * @var string Date format
         */
        private $format = null;

        /**
         * A list of valid date formats grouped by their type.
         * @var ArrayObject
         */
        private static $formatters = [
            'date'     => [
                'c',
                'r',
                'U'
            ],
            'year'     => [
                'Y',
                'y',
                'o'
            ],
            'month'    => [
                'F',
                'm',
                'M',
                'n',
                't'
            ],
            'week'     => ['W'],
            'day'      => [
                'd',
                'D',
                'j',
                'l',
                'N',
                'S',
                'w',
                'z'
            ],
            'time'     => ['H:i:s'],
            'hours'    => [
                'g',
                'G',
                'h',
                'H'
            ],
            'meridiem' => [
                'a',
                'A'
            ],
            'minutes'  => ['i'],
            'seconds'  => ['s']
        ];
        /**
         * @var null|ArrayObject
         */
        private $dateTimeFormat = null;

        /**
         * Constructor.
         * Set standard object value.
         *
         * @param string|int  $time A date/time string. List of available formats is explained here
         *                                              http://www.php.net/manual/en/datetime.formats.php
         * @param null|string $timezone Timezone in which you want to set the date. Here is a list of valid
         *                                              timezones: http://php.net/manual/en/timezones.php
         *
         * @throws DateTimeObjectException
         */
        public function __construct($time = "now", $timezone = null)
        {
            try {
                date_default_timezone_set(Config::getInstance()->get("timezone", "app"));

                // set the config
                $this->parseDateTimeFormat();

                // if UNIX timestamp - convert to string value
                if($this->isNumber($time) && $time <= PHP_INT_MAX && $time >= ~PHP_INT_MAX){
                    // Try converting timestamp to date string and back
                    $date = date('Y-m-d H:i:s', $time);
                    if ($date && strtotime($date)) {
                        $time = $date;
                    }
                }

                // get date timezone
                $this->entryTimezone = $this->createTimezone($timezone);
                $this->val(new \DateTime($time, $this->entryTimezone));

                // get UTC offset and correct the date to UTC by calculating the offset
                $this->timestamp = $this->getDateObject()->getTimestamp();
            } catch (\Exception $e){
                throw new DateTimeObjectException($e->getMessage());
            }
        }

        /**
         * Set the date format.
         *
         * @param string $format Date format. These are the valid options: http://php.net/manual/en/function.date.php
         *
         * @return $this
         */
        public function setFormat($format)
        {
            $this->format = $format;

            return $this;
        }

        /**
         * Set a new timezone for current date object.
         * NOTE: The current timestamp will be recalculated with the offset of current timezone and the new defined one.
         *
         * @param string|\DateTimeZone $timezone Timezone to which you wish to offset. You can either pass \DateTimeZone object
         *                                       or a valid timezone string. For timezone string formats
         *                                       visit: http://php.net/manual/en/timezones.php
         *
         * @return $this
         * @throws DateTimeObjectException
         */
        public function setTimezone($timezone)
        {
            try {
                if(!$this->isInstanceOf($timezone, 'DateTimeZone')){
                    $timezone = new \DateTimeZone($timezone);
                }

                $this->getDateObject()->setTimezone($timezone);
            } catch(\Exception $e) {
                throw new DateTimeObjectException(DateTimeObjectException::MSG_INVALID_TIMEZONE, [$timezone]);
            }

            return $this;
        }

        /**
         * Get date in full date format.
         *
         * @param null|string $format A valid date format.
         *
         * @return string Date in full date format like ISO 8691 or RFC 2822.
         */
        public function getDate($format = null)
        {
            return $this->getDateElement('date', $format);
        }


        /**
         * Get year based on current date.
         *
         * @param null|string $format A valid year format.
         *
         * @return string Year based on current date.
         */
        public function getYear($format = null)
        {
            return $this->getDateElement('year', $format);
        }

        /**
         * Get month based on current date.
         *
         * @param null|string $format A valid month format.
         *
         * @return string  Month based on current date.
         */
        public function getMonth($format = null)
        {
            return $this->getDateElement('month', $format);
        }

        /**
         * Get week number based on current date.
         *
         * @return int Wek number based on current date.
         */
        public function getWeek()
        {
            return $this->getDateElement('week');
        }

        /**
         * Get day based on current date.
         *
         * @param null|string $format A valid day format.
         *
         * @return string  Day based on current date.
         */
        public function getDay($format = null)
        {
            return $this->getDateElement('day', $format);
        }

        /**
         * Return time based on current date.
         *
         * @param null|string $format A valid time format.
         *
         * @return string Time based on current date.
         */
        public function getTime($format = null)
        {
            return $this->getDateElement('time', $format);
        }

        /**
         * Get hours based on current date.
         *
         * @param null|string $format A valid hour format.
         *
         * @return string Hours based on current date.
         */
        public function getHours($format = null)
        {
            return $this->getDateElement('hours', $format);
        }

        /**
         * Get meridiem (am, pm) based on current date.
         *
         * @param null|string $format A valid meridiem format.
         *
         * @return string Meridiem (am, pm) based on current date.
         */
        public function getMeridiem($format = null)
        {
            return $this->getDateElement('meridiem', $format);
        }

        /**
         * Get minutes based on current date.
         *
         * @return string Minutes based on current date
         */
        public function getMinutes()
        {
            return $this->getDateElement('minutes');
        }

        /**
         * Get seconds based on current date.
         *
         * @return string Seconds based on current date.
         */
        public function getSeconds()
        {
            return $this->getDateElement('seconds');
        }

        /**
         * Calculates the time passed between current date and $form (default: now).
         * The output is formatted in plain words, like "4 hours ago".
         *
         * @param null $from Timestamp from where to calculate the offset. Default is now.
         *
         * @return string String describing the passed time. Example "4 hours ago".
         */
        public function getTimeAgo($from = null)
        {
            $periods = [
                'second',
                'minute',
                'hour',
                'day',
                'week',
                'month',
                'year',
                'decade'
            ];
            $lengths = [
                '60',
                '60',
                '24',
                '7',
                '4.35',
                '12',
                '10'
            ];

            $now = ($this->isNull($from)) ? time() : strtotime($from);
            $unix_date = $this->getTimestamp();

            // is it future date or past date
            if ($now > $unix_date) {
                $difference = $now - $unix_date;
                $tense = "ago";

            } else {
                $difference = $unix_date - $now;
                $tense = "from now";
            }

            for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
                $difference /= $lengths[$j];
            }

            $difference = round($difference);

            if ($difference != 1) {
                $periods[$j] .= "s";
            }

            return $difference . ' ' . $periods[$j] . ' ' . $tense;
        }

        /**
         * Get UNIX timestamp based on current date.
         * @return int UNIX timestamp based on current date
         */
        public function getTimestamp()
        {
            return $this->getDateObject()->getTimestamp();
        }

        /**
         * Returns current \DateTime object.
         *
         * @return \DateTime|null
         */
        private function getDateObject()
        {
            return $this->value;
        }

        /**
         * Returns defined $dateElement in defined $format.
         *
         * @param string      $dateElement Possible values are: date, year, month, day, time, hour, minutes, seconds, meridiem.
         * @param null|string $format For list of possible formats check: http://php.net/manual/en/function.date.php
         *
         * @return string
         */
        private function getDateElement($dateElement, $format = null)
        {
            $format = ($this->isNull($format)) ? $this->getFormatFor($dateElement) : $this->validateFormatFor($dateElement, $format);

            return $this->getDateObject()->format($format);
        }

        /**
         * Returns format for defined $dateElement.
         *
         * @param string $dateElement Possible values are: date, year, month, day, time, hour, minutes, seconds, meridiem.
         *
         * @return mixed
         */
        private function getFormatFor($dateElement)
        {
            if ($this->dateTimeFormat->keyExists($dateElement)) {
                return $this->dateTimeFormat->key($dateElement);
            }

            return self::$formatters->key($dateElement)->first();
        }

        /**
         * To string implementation.
         *
         * @return mixed
         */
        public function __toString()
        {
            return $this->format($this->format);
        }

        /**
         * Return date in the given format.
         *
         * @param string $format A valid date format.
         *
         * @return string A string containing the date in the given $format.
         * @throws DateTimeObjectException
         */
        public function format($format)
        {
            try {
                return $this->getDateObject()->format($format);
            } catch (\Exception $e) {
                throw new DateTimeObjectException(DateTimeObjectException::MSG_INVALID_DATE_FORMAT, [$format]);
            }
        }

        /**
         * Return, or update, current standard objects value.
         *
         * @param null $value If $value is set, value is updated and DateTimeObject is returned.
         *
         * @return mixed
         */
        public function val($value = null)
        {
            if (!$this->isNull($value)) {
                $this->value = $value;

                return $this;
            }

            return $this->getDateObject()->format($this->format);
        }

        /**
         * This function parses the format provided by Config and sets the default formatting for getting date information
         * like day, month, year, etc..
         *
         * @throws DateTimeObjectException
         */
        private function parseDateTimeFormat()
        {
            try {
                if ($this->isNull($this->format)) {
                    $this->format = self::$defaultFormat;
                }

                $str = new StringObject($this->format);
                $chunks = $str->split();

                $this->buildFormatterList();

                foreach ($chunks as $c) {
                    foreach (self::$formatters as $fk => $f) {
                        if ($f->inArray($c)) {
                            $this->dateTimeFormat[$fk] = $c;
                        }
                    }
                }
                $this->dateTimeFormat = new ArrayObject($this->dateTimeFormat);
            } catch (\Exception $e) {
                throw new DateTimeObjectException(DateTimeObjectException::MSG_INVALID_DATE_FORMAT, [$this->format]);
            }
        }

        /**
         * Create a DateTimeZone object for the given $timeZone.
         * If $timezone is undefined, default timezone is returned. Default timezone is the servers timezone.
         *
         * @param string|null $timezone A valid time zone. For list of available timezones visit:
         *                              http://www.php.net/manual/en/timezones.php
         *
         * @return \DateTimeZone
         * @throws DateTimeObjectException
         */
        private function createTimezone($timezone = null)
        {
            try {
                if ($this->isNull($timezone)) {
                    if ($this->isNull(self::$defaultTimezone)) {
                        try {
                            $defaultTimezone = date_default_timezone_get();

                            self::$defaultTimezone = new \DateTimeZone($defaultTimezone);
                        } catch (\Exception $e) {
                            throw new DateTimeObjectException(DateTimeObjectException::MSG_DEFAULT_TIMEZONE);
                        }
                    }

                    return self::$defaultTimezone;
                } else {
                    return new \DateTimeZone($timezone);
                }
            } catch(\Exception $e){
                throw new DateTimeObjectException(DateTimeObjectException::MSG_INVALID_TIMEZONE, [$timezone]);
            }
        }

        /**
         * Reformats self::$formatters from array to ArrayObject.
         */
        private function buildFormatterList()
        {
            if (!$this->isObject(self::$formatters)) {
                $formatters = new ArrayObject([]);
                foreach (self::$formatters as $fk => $fv) {
                    $formatters->key($fk, new ArrayObject($fv));
                }
                self::$formatters = $formatters;
            }
        }

        /**
         * Checks if $format is a valid format for $dateElement.
         *
         * @param string $dateElement Possible values are: date, year, month, day, time, hour, minutes, seconds, meridiem.
         * @param string $format For list of possible formats check: http://php.net/manual/en/function.date.php
         *
         * @return mixed
         * @throws DateTimeObjectException
         */
        private function validateFormatFor($dateElement, $format)
        {
            if (!self::$formatters->key($dateElement)->inArray($format)) {
                throw new DateTimeObjectException(DateTimeObjectException::MSG_INVALID_FORMAT_FOR_ELEMENT, [
                    $format,
                    "get" . ucfirst($dateElement)
                ]);
            }

            return $format;
        }
    }