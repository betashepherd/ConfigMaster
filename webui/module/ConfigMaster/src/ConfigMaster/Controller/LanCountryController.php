<?php

namespace ConfigMaster\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ConfigMaster\Form\LanCountryForm;
use ConfigMaster\Form\LanCountryFormInputFilter;
use ConfigMaster\PageLogic\LanCountry as LanCountryLogic;

class LanCountryController extends AbstractActionController
{
    protected $_pagelogic = null;
    
    public function __construct()
    {
        $this->_pagelogic = new LanCountryLogic();
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
                $this->_pagelogic->create($this->_pagelogic->exchangeArray($form->getData()));
                return $this->redirect()->toRoute('dashboard/default', array('controller' => 'lancountry', 'action' => 'list'));
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

