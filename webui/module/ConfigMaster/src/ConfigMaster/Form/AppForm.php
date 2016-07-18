<?php

namespace ConfigMaster\Form;

use Zend\Form\Form;

class AppForm extends Form {
    public function __construct($name = null) {
        parent::__construct('appform');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));

        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
            ),
            'attributes' => array(
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
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => '保存',
                'id' => 'saveapp',
                'class' => 'btn btn-default btn-lg btn-block'
            ),
        ));
    }
}
