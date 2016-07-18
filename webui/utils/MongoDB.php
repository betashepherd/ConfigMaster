<?php

namespace Utils;

class MongoDB {
    
    /**
     * mongo db client
     * @var \MongoDB\Client
     */
    public static $instance;
    
    /**
     * tool reg
     * @var bool
     */
    private static $registered = false;
    
    /**
     * @return \MongoDB\Client
     */
    public static function getInstance() {
        if (self::$registered === false) {
            $app_config = array(
                'mongodb' => array(
                    'uri' => 'mongodb://localhost:27017',
                    'uriOptions' => array(),
                    'driverOptions' => array()
                )
            );
            self::$instance   = new \MongoDB\Client($app_config['mongodb']['uri'], $app_config['mongodb']['uriOptions'], $app_config['mongodb']['driverOptions']);
            self::$registered = true;
        }

        return self::$instance;
    }
    
    private function __construct() {
        
    }
    
    private function __clone() {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }
}