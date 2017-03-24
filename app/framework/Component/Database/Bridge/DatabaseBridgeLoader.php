<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Database\Bridge;

    use app\framework\Component\Database\Bridge\Medoo\Medoo;

    class DatabaseBridgeLoader
    {
        public static function getBridge($Bridge)
        {
            switch($Bridge){
                case 'Medoo':
                    return new Medoo();
                    break;
                default:
                    // Exception
                    break;
            }
        }
    }