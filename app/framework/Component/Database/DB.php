<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Database;

    use app\framework\Component\Database\Bridge\DatabaseBridgeLoader;
    use app\framework\Component\StdLib\StdLibTrait;
    use app\framework\Component\Config\Config;

    class DB
    {
        private $bridge;

        function __construct($databaseName, $useBridge = 'Medoo')
        {
            $Conf = new Config();
            $config = $Conf->get("database");

            $this->bridge = DatabaseBridgeLoader::getBridge($useBridge, $config[$databaseName]);
        }

        public function getBridge()
        {
            return $this->bridge;
        }
    }