<?php

namespace ConfigMaster\PageLogic;

use ConfigMaster\DataLogic\Config as ConfigDataLogic;
use ConfigMaster\DataStruct\Config as ConfigDataStruct;

class Config {
    
    /**
     * config data logic
     * @var ConfigDataLogic
     */
    protected $_config;
    
    /**
     * config data
     * @var ConfigDataStruct
     */
    protected $_configData;
    
    public function __construct() {
        $this->_config = new ConfigDataLogic();
        $this->_configData = new ConfigDataStruct();
    }
    
    /**
     * get all config
     * @param number $appid
     * @return \MongoDB\Driver\Cursor
     */
    public function fetchAll($appid = 0) {
        return $this->_config->fetchAll($appid);
    }

    /**
     * 
     * @param array $data
     * @return \ConfigMaster\DataStruct\Config
     */
    public function exchangeArray(array $data) {
        $id = (isset($data['id'])) ? $data['id'] : null;
        $appid = (isset($data['appid'])) ? $data['appid'] : null;
        $path = (isset($data['path'])) ? $data['path'] : null;
        $key = (isset($data['key'])) ? $data['key'] : null;
        $value = (isset($data['value'])) ? $data['value'] : null;
        $desc = (isset($data['desc'])) ? $data['desc'] : null;
        $operator = (isset($data['operator'])) ? $data['operator'] : 'administrator';
        $version = (isset($data['version'])) ? $data['version'] : 1;
        $timestamp = time();
        
        $this->_configData->setId($id);
        $this->_configData->setAppid($appid);
        $this->_configData->setPath($path);
        $this->_configData->setKey($key);
        $this->_configData->setValue($value);
        $this->_configData->setDesc($desc);
        $this->_configData->setOperator($operator);
        $this->_configData->setVersion($version);
        $this->_configData->setTimestamp($timestamp);
        $this->_configData->setStatus(0);//  默认未下发
        
        return $this->_configData;
        
    }
    
    /**
     * create config
     * @param ConfigDataStruct $config
     * @return \MongoDB\InsertOneResult
     */
    public function create(ConfigDataStruct $config) {
        if($this->isKeyExists($config)) {
            throw new \Exception('key already exists in this app', 3000);
        }
        return $this->_config->save($config);
    }
    
    /**
     * 
     * @param ConfigDataStruct $config
     * @return Ambigous <\ConfigMaster\DataLogic\Ambigous, multitype:, object, NULL, mixed>
     */
    public function isKeyExists(ConfigDataStruct $config) {
        $filter = array('appid' => $config->getAppid(), 'key' => $config->getKey());
        $result = $this->_config->getConfigByFilterAndOptions($filter);
        return empty($result) ? false : true;
    }
    
    /**
     * update config
     * @param ConfigDataStruct $config
     * @return Ambigous <\MongoDB\InsertOneResult, \MongoDB\UpdateResult>
     */
    public function edit(ConfigDataStruct $config) {
        return $this->_config->save($config);
    }
    
    /**
     * get config
     * @param string $id
     * @return Ambigous <\ConfigMaster\DataLogic\Ambigous, multitype:, object, NULL, mixed>
     */
    public function getConfig($id) {
        return $this->_config->getConfig($id);
    }
    
    /**
     * delete config
     * @param string $id
     * @return \MongoDB\DeleteResult
     */
    public function delete($id) {
        return $this->_config->delete($id);
    }
    
    /**
     * apply config
     * @param string $id
     * @return Ambigous <\MongoDB\DeleteResult, object, NULL>
     */
    public function apply($id, $version) {
        return $this->_config->apply($id, $version);
    }
    
    /**
     * get his version
     * @param string $configId
     * @return multitype:Ambigous <>
     */
    public function fetchHis($configId) {
        $configHis = $this->_config->getConfigHis($configId);
        $version = array();
        foreach ($configHis as $config) {
            $version[$config['_id']->__toString()] = $config['version'];
        }
        return $version;
    }
    
}