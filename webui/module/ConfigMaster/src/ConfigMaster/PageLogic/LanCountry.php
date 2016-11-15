<?php

namespace ConfigMaster\PageLogic;

use ConfigMaster\DataLogic\LanCountry as LanCountryDataLogic;
use ConfigMaster\DataStruct\LanCountry as LanCountryDataStruct;

class LanCountry {
    
    /**
     * data logic
     * @var LanCountryDataLogic
     */
    protected $_datalogic;
    
    /**
     * lan country
     * @var LanCountryDataStruct
     */
    protected $_datastruct;
    
    public function __construct() {
        $this->_datalogic = new LanCountryDataLogic();
        $this->_datastruct = new LanCountryDataStruct();
    }
    
    /**
     * get all lan country list
     * @return \MongoDB\Driver\Cursor
     */
    public function fetchAll() {
        return $this->_datalogic->fetchAll();
        
    }

    /**
     * gen lancountry data struct
     * @param array $data
     * @return \ConfigMaster\DataStruct\LanCountry
     */
    public function exchangeArray(array $data) {
        $id = (isset($data['id'])) ? $data['id'] : null;
        $appname = (isset($data['name'])) ? $data['name'] : null;
        $desc = (isset($data['desc'])) ? $data['desc'] : null;
        $operator = (isset($data['operator'])) ? $data['operator'] : 'administrator';
        $timestamp = time();
        
        $this->_datastruct->setId($id);
        $this->_datastruct->setName($appname);
        $this->_datastruct->setDesc($desc);
        $this->_datastruct->setOperator($operator);
        $this->_datastruct->setTimestamp($timestamp);
        
        return $this->_datastruct;
        
    }
    
    /**
     * create lan country
     * @param LanCountryDataStruct $data
     * @return Ambigous <\MongoDB\InsertOneResult, object, NULL>
     */
    public function create(LanCountryDataStruct $data) {
        return $this->_datalogic->save($data);
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