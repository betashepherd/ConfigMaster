<?php

namespace ConfigMaster\Form;

use ConfigMaster\DataLogic\App as AppDataLogic;
use Zend\Form\Form;

class ConfigForm extends Form {
    public function __construct($name = null) {
        parent::__construct('configform');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));
        
        $this->add(array(
            'name' => 'version',
            'type' => 'Hidden'
        ));
        
        $appDataLogic = new AppDataLogic();
        $apps = $appDataLogic->fetchAll();
        
        $app_select= array( 0 => '请选择App');
        
        foreach ($apps as $key => $app) {
            $app_select[$app['_id']->__toString()] = $app['name'];
        }
        
        if(count($app_select) == 1) {
            $app_select= array( 0 => '暂无App, 请在应用管理中创建应用');
        }
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                //'multiple' => 'multiple',
                'class' => 'form-control'
            ),
            'name' => 'appid',
            'options' => array(
                //'label' => 'langs',
                'value_options' => $app_select
            ),
        ));
        $this->add(array(
            'name' => 'path',
            'type' => 'Text',
            'options' => array(
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));
        $this->add(array(
            'name' => 'key',
            'type' => 'Text',
            'options' => array(
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));
        $this->add(array(
            'name' => 'value',
            'type' => 'Textarea',
            'options' => array(
            ),
            'attributes' => array(
                'rows' => 15,
                'class' => 'form-control'
            )
        ));
        $this->add(array(
            'name' => 'desc',
            'type' => 'Textarea',
            'options' => array(
            ),
            'attributes' => array(
                'rows' => 5,
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'status',
            'type' => 'Hidden'
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => '保存',
                'id' => 'saveconfig',
                'class' => 'btn btn-default btn-lg btn-block'
            ),
        ));
    }
}
