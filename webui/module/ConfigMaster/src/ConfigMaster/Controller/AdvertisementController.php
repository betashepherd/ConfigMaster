<?php

namespace ConfigMaster\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdvertisementController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }


}

