<?php
/**
 *
 */
class Config
{

    private static $config = null;

    function __construct()
    {
        $this->loadConfig();
    }

    public function instance(){
        if(self::$config == null){
            self::$config = new Config();
        }
        return self::$config;
    }

    public function getDatabaseConnectionString(){
        $dbcfg = self::getDatabaseConfiguration();
        if(isset($dbcfg->DSN)){
            return $dbcfg->DSN;
        } else {
            return $dbcfg->prefix.$dbcfg->server.':'.$dbcfg->port.$dbcfg->instanceSeparator.$dbcfg->instance;
        }
    }

    public function getDatabaseUser(){
        return self::getDatabaseConfiguration()->username;
    }

    public function getDatabasePass(){
        return self::getDatabaseConfiguration()->password;
    }

    public function getAllowedIP()
    {
        return self::getSecurity()->allowedIP;
    }

    public function getUsername(){
        return self::getSecurity()->username;
    }

    public function getPassword(){
        return self::getSecurity()->password;
    }

    private function getSecurity(){
        return self::instance()->security;
    }


    private function getDatabaseConfiguration(){
        return self::instance()->db;
    }


    /**
    * Look out for a constant called CONFIG. If it's not defined, load Configuration file from ../config.php
    **/
    private function loadConfig(){
        if(defined(CONFIG)) return true;
        $configFile = dirname(__FILE__)."/../config.php";
        require_once($configFile);
        if(null !== CONFIG){
            $config = unserialize(CONFIG);
            self::arrayAsProprieties($this,$config);
            return true;
        }
        throw new Exception("Configuration is not defined and could not be loaded from $configFile.", 1);
    }

    private function arrayAsProprieties($obj, $array){
        if($obj == null || $array == null || !is_array($array)) return;
        foreach ($array as $key => $value) {
            if(is_array($value)){
                if(!isset($obj->$key)) $obj->$key = new stdClass();
                self::arrayAsProprieties($obj->$key, $value);
            } else
                $obj->$key = $value;
        }
    }
}

 ?>
