<?php

namespace ConfigMaster\DataLogic;

use ConfigMaster\DataStruct\App as AppDataStruct;
use Utils\MongoDB;

class App {
    
    /**
     * get all config
     * @return \MongoDB\Driver\Cursor
     */
    public function fetchAll() {
        $filter  = array();
        $options = array('sort' => array('timestamp' => -1));
        return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('app')->find($filter, $options);
    }
    
    /**
     * save app
     * @param AppDataStruct $app
     * @return \MongoDB\InsertOneResult
     */
    public function save(AppDataStruct $app) {
        $appId = $app->getId();
        
        if(empty($appId)) {
            unset($app->id);
            return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('app')->insertOne($app);
        } else {
            $filter  = array('_id' => new \MongoDB\BSON\ObjectID($appId));
            $options = array('upsert' => true);
            unset($app->id);
            $app->_id = new \MongoDB\BSON\ObjectID($appId);
            $update = array('$set' => $app);
            return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('app')->findOneAndUpdate($filter, $update, $options);
        }
        
    }
    
    /**
     * del app
     * @param string $id
     * @return \MongoDB\DeleteResult
     */
    public function delete($id) {
        $filter  = array('_id' => new \MongoDB\BSON\ObjectID($id));
        $options = array();
        return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('app')->deleteOne($filter, $options);
    }
    

    /**
     * get album info
     * @param string $albumId
     * @return Ambigous <multitype:, object, NULL, mixed>
     */
    public function getApp($id) {
        $filter  = array('_id' => new \MongoDB\BSON\ObjectID($id));
        $options = array();
        return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('app')->findOne($filter, $options);
    }
}