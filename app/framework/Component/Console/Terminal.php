<?php
/**
 *
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console;

    class Terminal
    {
        private static $width;
        private static $height;

        private static $descriptorSpec = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        public function getWidth()
        {
            $width = getenv('COLUMNS');

            if ($width !== false)
                return (int) trim($width);

            if(self::$width === null)
                self::initDimensions();

            return self::$width ?: 80;
        }

        public function getHeight()
        {
            $height = getenv('ROWS');

            if($height !== false)
                return (int) $height;

            if(self::$height !== false)
                self::initDimensions();

            return self::$height ?: 60;
        }

        private static function initDimensions()
        {
            if ('\\' === DIRECTORY_SEPARATOR) {
                if (preg_match('/^(\d+)x(\d+)(?: \((\d+)x(\d+)\))?$/', trim(getenv('ANSICON')), $matches)) {
                    // extract [w, H] from "wxh (WxH)"
                    // or [w, h] from "wxh"
                    self::$width = (int) $matches[1];
                    self::$height = isset($matches[4]) ? (int) $matches[4] : (int) $matches[2];
                } elseif (null !== $dimensions = self::getConsoleMode()) {
                    // extract [w, h] from "wxh"
                    self::$width = (int) $dimensions[0];
                    self::$height = (int) $dimensions[1];
                }
            } elseif ($sttyString = self::getSttyColumns()) {
                if (preg_match('/rows.(\d+);.columns.(\d+);/i', $sttyString, $matches)) {
                    // extract [w, h] from "rows h; columns w;"
                    self::$width = (int) $matches[2];
                    self::$height = (int) $matches[1];
                } elseif (preg_match('/;.(\d+).rows;.(\d+).columns/i', $sttyString, $matches)) {
                    // extract [w, h] from "; h rows; w columns"
                    self::$width = (int) $matches[2];
                    self::$height = (int) $matches[1];
                }
            }
        }

        /**
         * Runs and parses mode CON if it's available, suppressing any error output.
         *
         * @return int[]|null An array composed of the width and the height or null if it could not be parsed
         */
        private static function getConsoleMode()
        {
            if (!function_exists('proc_open')) {
                return;
            }

            $process = proc_open('mode CON', self::$descriptorSpec, $pipes, null, null, ['suppress_errors' => true]);

            if (is_resource($process)) {
                $info = stream_get_contents($pipes[1]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);
                if (preg_match('/--------+\r?\n.+?(\d+)\r?\n.+?(\d+)\r?\n/', $info, $matches)) {
                    return array((int) $matches[2], (int) $matches[1]);
                }
            }
        }

        /**
         * Runs and parses stty -a if it's available, suppressing any error output.
         *
         * @return string|null
         */
        private static function getSttyColumns()
        {
            if (!function_exists('proc_open')) {
                return;
            }

            $process = proc_open('stty -a | grep columns', self::$descriptorSpec, $pipes, null, null, ['suppress_errors' => true]);

            if (is_resource($process)) {
                $info = stream_get_contents($pipes[1]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);

                return $info;
            }
        }
    }