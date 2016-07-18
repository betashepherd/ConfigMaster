<?php

namespace ConfigMaster\DataLogic;

use ConfigMaster\DataStruct\Config as ConfigDataStruct;
use Utils\MongoDB;

class Config {
    /**
     * get all config
     * @return \MongoDB\Driver\Cursor
     */
    public function fetchAll($appid = 0) {
        $filter  = empty($appid) ? array() : array('appid' => $appid);
        $options = array('sort' => array('timestamp' => -1));
        return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('config')->find($filter, $options);
    }
    
    /**
     * save config
     * @param ConfigDataStruct $config
     * @return \MongoDB\InsertOneResult
     */
    public function save(ConfigDataStruct $config) {
        $configId = $config->getId();
        unset($config->id);
        if(empty($configId)) { 
            // create
            $result = MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('config')->insertOne($config);
            $result_his = MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('config_his')->insertOne($config);
        } else { 
            // update
            $filter  = array('_id' => new \MongoDB\BSON\ObjectID($configId));
            $options = array('upsert' => true);
            $config->_id = new \MongoDB\BSON\ObjectID($configId);
            $config->setVersion($config->getVersion() + 1);
            $update = array('$set' => $config);
            $result = MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('config')->findOneAndUpdate($filter, $update, $options);
            unset($config->_id);
            $result_his = MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('config_his')->insertOne($config);
        }
        
    }
    
    /**
     * del config
     * @param string $id
     * @return \MongoDB\DeleteResult
     */
    public function delete($id) {
        $filter  = array('_id' => new \MongoDB\BSON\ObjectID($id));
        $options = array();
        return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('config')->deleteOne($filter, $options);
    }
    

    /**
     * apply config
     * @param string $id
     * @param string $version
     * @return Ambigous <object, NULL>
     */
    public function apply($id, $version) {
        $filter  = array('_id' => new \MongoDB\BSON\ObjectID($id));
        $options = array();
        $update = array('$set' => array('status' => 1));
        MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('config_his')->findOneAndUpdate($filter, $update, $options);
        $configHis = $this->getConfig($id, 'config_his');
        $filter2  = array('appid' => $configHis['appid'], 'key' => $configHis['key']);
        $update2 = array('$set' => array('version' => $configHis['version']));
        return $result = MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('config')->findOneAndUpdate($filter2, $update2, $options);
    }
    
    /**
     * get album info
     * @param string $albumId
     * @return Ambigous <multitype:, object, NULL, mixed>
     */
    
    /**
     * get config content
     * @param string $id
     * @param string $collection
     * @return Ambigous <multitype:, object, NULL, mixed>
     */
    public function getConfig($id, $collection = 'config') {
        $filter  = array('_id' => new \MongoDB\BSON\ObjectID($id));
        $options = array();
        return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection($collection)->findOne($filter, $options);
    }
    
    /**
     * get data by filter and options
     * @param array $filter
     * @param array $options
     * @return Ambigous <multitype:, object, NULL, mixed>
     */
    public function getConfigByFilterAndOptions($filter, $options = array()) {
        return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('config')->findOne($filter, $options);
    }
    

    /**
     * get config his
     * @param string $id
     * @return Ambigous <multitype:, object, NULL, mixed>
     */
    public function getConfigHis($configId) {
        $filter  = array('_id' => new \MongoDB\BSON\ObjectID($configId));
        $options = array();
        $config = MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('config')->findOne($filter, $options);
        $filter = array('appid' => $config['appid'], 'key' => $config['key']);
        $options = array('sort' => array('version' => -1));
        return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('config_his')->find($filter, $options);
    }
    
}