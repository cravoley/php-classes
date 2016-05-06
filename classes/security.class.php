<?php
/**
 *
 */
class Security
{
    var $config = array();

    function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function isAllowed(){
        return $this->isAllowedIP() && $this->isAllowedUser() && $this->isAllowedMethod();
    }

    private function isAllowedMethod(){
        // we accept only GET requests
        // if(array_key_exists("REQUEST_METHOD",$_SERVER) && $_SERVER["REQUEST_METHOD"] == "GET"){
        //     if(array_key_exists("modelo", $_GET)){
        //         $modelo = $_GET["modelo"];
        //
        //     }
        // }
        return array_key_exists("REQUEST_METHOD",$_SERVER) && $_SERVER["REQUEST_METHOD"] == "GET";
    }

    private function isAllowedUser(){
        // get user and password from request because that's the way the webservice client was created
        if(array_key_exists("usuario", $_GET) && array_key_exists("senha",$_GET)){
            $username = $_GET["usuario"];
            $password = $_GET["senha"];
            return $this->config->getUsername() == $username && $this->config->getPassword() == $password;
        }
        return false;
    }

    private function isAllowedIP(){
        if(array_key_exists("REMOTE_ADDR", $_SERVER)){
            $requestIP = $_SERVER["REMOTE_ADDR"];
            $allowedIP = $this->config->getAllowedIP();
            if(is_array($allowedIP)){
                // return false if value is not present in the array
                return !(false === array_search($requestIP, $allowedIP, true));
            } else
                return $requestIP == $allowedIP;
        }
        return false;
    }

}
 ?>
