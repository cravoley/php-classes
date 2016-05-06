<?php

require_once(dirname(__FILE__).'/config.class.php');

class Database extends PDO {

    private static $instance=null;

    public function __construct(Config $config)
    {
        parent::__construct($config->getDatabaseConnectionString(), $config->getDatabaseUser(), $config->getDatabasePass());
    }

    public static function instance(){
        if (self::$instance === null) {
            self::$instance = new Database(Config::instance());
        }
        return self::$instance;
    }

    public function clean($content='')
    {
        return self::quote($content);
    }
}
