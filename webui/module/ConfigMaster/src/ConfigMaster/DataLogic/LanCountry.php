<?php

namespace ConfigMaster\DataLogic;

use ConfigMaster\DataStruct\LanCountry as LanCountryDataStruct;
use Utils\MongoDB;

class LanCountry {
    
    /**
     * get all config
     * @return \MongoDB\Driver\Cursor
     */
    public function fetchAll() {
        $filter  = array();
        $options = array('sort' => array('timestamp' => -1));
        return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('lancountry')->find($filter, $options);
    }
    
    /**
     * save lan country
     * @param LanCountryDataStruct $data
     * @return \MongoDB\InsertOneResult|Ambigous <object, NULL>
     */
    public function save(LanCountryDataStruct $data) {
        $id = $data->getId();
        
        if(empty($id)) {
            unset($data->id);
            return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('lancountry')->insertOne($data);
        } else {
            $filter  = array('_id' => new \MongoDB\BSON\ObjectID($id));
            $options = array('upsert' => true);
            unset($app->id);
            $app->_id = new \MongoDB\BSON\ObjectID($id);
            $update = array('$set' => $data);
            return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('lancountry')->findOneAndUpdate($filter, $update, $options);
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
        return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('lancountry')->deleteOne($filter, $options);
    }
    

    /**
     * get album info
     * @param string $albumId
     * @return Ambigous <multitype:, object, NULL, mixed>
     */
    public function getApp($id) {
        $filter  = array('_id' => new \MongoDB\BSON\ObjectID($id));
        $options = array();
        return MongoDB::getInstance()->selectDatabase('configmaster')->selectCollection('lancountry')->findOne($filter, $options);
    }
}