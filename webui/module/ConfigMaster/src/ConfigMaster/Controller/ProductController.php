<?php

namespace ConfigMaster\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ConfigMaster\Form\AppForm;
use ConfigMaster\Form\AppFormInputFilter;
use ConfigMaster\PageLogic\App as AppPageLogic;

class ProductController extends AbstractActionController
{
    protected $_app = null;
    
    public function __construct()
    {
        $this->_app = new AppPageLogic();
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function createAction()
    {
        $form = new AppForm();
        if($this->getRequest()->isPost()) {
            $appFormInputFilter = new AppFormInputFilter();
            $form->setInputFilter($appFormInputFilter->getInputFilter());
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $this->_app->create($this->_app->exchangeArray($form->getData()));
                return $this->redirect()->toRoute('dashboard/default', array('controller' => 'product', 'action' => 'list'));
            } else {
                $form->get('submit')->setValue('保存');
                return new ViewModel(array('form' => $form));
            }
        } else {
            $form->get('submit')->setValue('保存');
            return new ViewModel(array('form' => $form));
        }
        return new ViewModel();
    }

    public function listAction()
    {
        return new ViewModel(array(
            'app' => $this->_app->fetchAll()
        ));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', null);
    
        if(empty($id)) {
            return $this->redirect()->toRoute('dashboard/default', array('controller' => 'product', 'action' => 'list'));
        }
    
        if($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del', 'No');
            if($del == 'Yes') {
                $this->_app->delete($id);
            }
            return $this->redirect()->toRoute('dashboard/default', array('controller' => 'product', 'action' => 'list'));
        }
    
        return new ViewModel(array('id' => $id, 'app' => $this->_app->getApp($id)));
    }
    
    public function editAction()
    {
    
        $id = $this->params()->fromRoute('id', null);
    
        if(empty($id)) {
            return $this->redirect()->toRoute('dashboard/default', array('controller' => 'product', 'action' => 'list'));
        }
    
        try {
            $app = $this->_app->getApp($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('dashboard/default', array('controller' => 'product', 'action' => 'list'));
        }
        
        $app['id'] = $app['_id']->__toString();
        unset($app['_id']);
        
        $form = new AppForm();
        
        if($this->getRequest()->isPost()) {
            $appFormInputFilter = new AppFormInputFilter();
            $form->setInputFilter($appFormInputFilter->getInputFilter());
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $this->_app->edit($this->_app->exchangeArray($form->getData()));
                return $this->redirect()->toRoute('dashboard/default', array('controller' => 'product', 'action' => 'list'));
            } else {
                $form->bind($app);
                $form->get('submit')->setAttribute('value', '保存');
                return new ViewModel(array('id' => $id, 'form' => $form));
            }
        } else {
            $form->bind($app);
            $form->get('submit')->setAttribute('value', '保存');
            return new ViewModel(array('id' => $id, 'form' => $form));
        }
    }
    
}

