<?php

namespace ConfigMaster\PageLogic;

use ConfigMaster\DataLogic\App as AppDataLogic;
use ConfigMaster\DataStruct\App as AppDataStruct;

class App {
    
    /**
     * app data logic
     * @var AppDataLogic
     */
    protected $_app;
    
    /**
     * app data
     * @var AppDataStruct
     */
    protected $_appData;
    
    public function __construct() {
        $this->_app = new AppDataLogic();
        $this->_appData = new AppDataStruct();
    }
    
    /**
     * get all config
     * @return \MongoDB\Driver\Cursor
     */
    public function fetchAll() {
        return $this->_app->fetchAll();
        
    }

    /**
     * 
     * @param array $data
     * @return \ConfigMaster\DataStruct\App
     */
    public function exchangeArray(array $data) {
        $id = (isset($data['id'])) ? $data['id'] : null;
        $appname = (isset($data['name'])) ? $data['name'] : null;
        $desc = (isset($data['desc'])) ? $data['desc'] : null;
        $operator = (isset($data['operator'])) ? $data['operator'] : 'administrator';
        $timestamp = time();
        
        $this->_appData->setId($id);
        $this->_appData->setName($appname);
        $this->_appData->setDesc($desc);
        $this->_appData->setOperator($operator);
        $this->_appData->setTimestamp($timestamp);
        
        return $this->_appData;
        
    }
    
    /**
     * create app
     * @param AppDataStruct $app
     * @return \MongoDB\InsertOneResult
     */
    public function create(AppDataStruct $app) {
        return $this->_app->save($app);
    }
    
    /**
     * update app
     * @param AppDataStruct $app
     * @return Ambigous <\MongoDB\InsertOneResult, \MongoDB\UpdateResult>
     */
    public function edit(AppDataStruct $app) {
        return $this->_app->save($app);
    }
    
    /**
     * delete app
     * @param string $id
     * @return \MongoDB\DeleteResult
     */
    public function delete($id) {
        return $this->_app->delete($id);
    }
    
    /**
     * get config
     * @param string $id
     * @return Ambigous <\ConfigMaster\DataLogic\Ambigous, multitype:, object, NULL, mixed>
     */
    public function getApp($id) {
        return $this->_app->getApp($id);
    }
    
}