<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use ConfigMaster\PageLogic\Config as ConfigPageLogic;

class IndexController extends AbstractRestfulController
{
    
    protected $_config = null;
    
    public function __construct()
    {
        $this->_config = new ConfigPageLogic();
    }

    public function indexAction()
    {
        return new JsonModel(array(
            'status' => array('code' => 0, 'msg' => 'access deny :('),
            'data' => null
        ));
    }

    public function configAction()
    {
        $configId = $this->params()->fromRoute('id', null);
        $result = $this->_config->fetchHis($configId);
        return new JsonModel($result);
    }
    
    public function applyconfigAction() {
        $version = $this->params()->fromQuery('version', null);
        $configId = $this->params()->fromQuery('id', null);
        $this->_config->apply($configId, $version);
        $result = array('code' => 1, 'msg' => '');
        return new JsonModel($result);
    }
}

