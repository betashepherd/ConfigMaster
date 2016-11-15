<?php

namespace ConfigMaster\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ConfigMaster\Form\LanCountryForm;
use ConfigMaster\Form\LanCountryFormInputFilter;

class CountryController extends AbstractActionController
{
    protected $_pagelogic = null;
    
    public function __construct()
    {
        //$this->_pagelogic = new LanCountryLogic();
    }
    
    public function indexAction()
    {
        return new ViewModel();
    }

    public function importAction()
    {
        return new ViewModel();
    }

    public function listAction()
    {
        return new ViewModel();
    }

    public function createAction()
    {
        $form = new LanCountryForm();
        if($this->getRequest()->isPost()) {
            $inputfilter = new LanCountryFormInputFilter();
            $form->setInputFilter($inputfilter->getInputFilter());
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $this->_app->create($this->_app->exchangeArray($form->getData()));
                return $this->redirect()->toRoute('dashboard/default', array('controller' => 'channel', 'action' => 'list'));
            } else {
                $form->get('submit')->setValue('保存');
                return new ViewModel(array('form' => $form));
            }
        } else {
            $form->get('submit')->setValue('保存');
            return new ViewModel(array('form' => $form));
        }
    }


}

