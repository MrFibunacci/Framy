<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Config;

    use app\framework\Component\StdLib\SingletonTrait;
    use app\framework\Component\StdLib\StdLibTrait;

    /**
     * This class is used to interact with config files
     *
     * @package app\framework\Component\Config
     */
    class Config
    {
        use StdLibTrait,SingletonTrait;

        private $data;

        public function get($config, $file = null)
        {
            $this->getAllData();
            //var_dump($this->data->val());
            if(!$this->isNull($file)){
                $file = $this->arr($this->data->key($file));

                return $file->key($config);
            } else {
                foreach($this->data->val() as $confCont){
                    $confCont = $this->arr($confCont);
                    if($confCont->keyExists($config)){
                        return $confCont->key($config);
                    }
                }
            }

            return false;
        }

        private function getAllData()
        {
            $files = $this->arr(scandir(ROOT_PATH.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR));
            $files->removeFirst()->removeFirst();

            foreach($files->val() as $file){
                $temp[explode(".", $file)[0]] = include(ROOT_PATH.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR.$file);
            }

            $this->data = $this->arr($temp);
        }
    }
