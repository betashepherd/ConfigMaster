<?php

namespace ConfigMaster\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ConfigMaster\PageLogic\Config as ConfigPageLogic;
use ConfigMaster\Form\ConfigForm;
use ConfigMaster\Form\ConfigFormInputFilter;
use ConfigMaster\PageLogic\App as AppPageLogic;

class ConfigController extends AbstractActionController
{

    protected $_config = null;
    protected $_app = null;

    public function __construct()
    {
        $this->_config = new ConfigPageLogic();
        $this->_app    = new AppPageLogic();
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function createAction()
    {
        $form = new ConfigForm();
        if($this->getRequest()->isPost()) {
            $configFormInputFilter = new ConfigFormInputFilter();
            $form->setInputFilter($configFormInputFilter->getInputFilter());
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                try {
                    $this->_config->create($this->_config->exchangeArray($form->getData()));
                    return $this->redirect()->toRoute('dashboard/default', array('controller' => 'config', 'action' => 'list'));
                } catch (\Exception $e) {
                    $form->setMessages(array('key' => array($e->getMessage())));
                    $form->get('submit')->setValue('保存');
                    return new ViewModel(array('form' => $form));
                }
            } else {
                $form->get('submit')->setValue('保存');
                return new ViewModel(array('form' => $form));
            }
        } else {
            $form->get('submit')->setValue('保存');
            return new ViewModel(array('form' => $form));
        }
    }

    public function listAction()
    {
        $appid = $this->params()->fromQuery('appid', 0);
        
        $appname = array();
        foreach ($this->_app->fetchAll() as $a) {
            $appname[$a['_id']->__toString()] = $a['name'];
        }
        
        return new ViewModel(array(
            'app' => $this->_app->fetchAll(),
            'appid' => $appid,
            'appname' => $appname,
            'config' => $this->_config->fetchAll($appid)
        ));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', null);

        if(empty($id)) {
            return $this->redirect()->toRoute('dashboard/default', array('controller' => 'config', 'action' => 'list'));
        }

        if($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del', 'No');
            if($del == 'Yes') {
                $this->_config->delete($id);
                return $this->redirect()->toRoute('dashboard/default', array('controller' => 'config', 'action' => 'list'));
            } else {
                return $this->redirect()->toRoute('dashboard/default', array('controller' => 'config', 'action' => 'list'));
            }
        }

        return new ViewModel(array('id' => $id, 'config' => $this->_config->getConfig($id)));
    }
    
    public function applyAction()
    {
        $id = $this->params()->fromRoute('id', null);
    
        if(empty($id)) {
            return $this->redirect()->toRoute('dashboard/default', array('controller' => 'config', 'action' => 'list'));
        }
    
        if($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('apply', 'No');
            if($del == 'Yes') {
                $this->_config->apply($id);
                return $this->redirect()->toRoute('dashboard/default', array('controller' => 'config', 'action' => 'list'));
            } else {
                return $this->redirect()->toRoute('dashboard/default', array('controller' => 'config', 'action' => 'list'));
            }
        }
    
        return new ViewModel(array('id' => $id, 'config' => $this->_config->getConfig($id)));
    }

    public function editAction()
    {

        $id = $this->params()->fromRoute('id', null);
        
        if(empty($id)) {
            return $this->redirect()->toRoute('dashboard/default', array('controller' => 'config', 'action' => 'list'));
        }
        
        try {
            $config = $this->_config->getConfig($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('dashboard/default', array('controller' => 'config', 'action' => 'list'));
        }
        $config['id'] = $config['_id']->__toString();
        unset($config['_id']);
        
        $form = new ConfigForm();
        
        if($this->getRequest()->isPost()) {
            $configFormInputFilter = new ConfigFormInputFilter();
            $form->setInputFilter($configFormInputFilter->getInputFilter());
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $this->_config->edit($this->_config->exchangeArray($form->getData()));
                return $this->redirect()->toRoute('dashboard/default', array('controller' => 'config', 'action' => 'list'));
            } else {
                $form->bind($config);
                $form->get('submit')->setAttribute('value', '保存');
                return new ViewModel(array('id' => $id, 'form' => $form));
            }
        } else {
            $form->bind($config);
            $form->get('submit')->setAttribute('value', '保存');
            return new ViewModel(array('id' => $id, 'form' => $form));
        }
        
    }


}

