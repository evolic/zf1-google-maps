<?php

/**
 * Extended Zend Form class
 * @author    "Tomasz Kuter" <evolic@interia.pl>
 * @copyright 2013 Tomasz Kuter
 */
class Form_SubForm extends App_Form_BaseForm
{
    public function init()
    {
        parent::init();

        $this->setAction('/');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
            ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Save');
        $cancel = new Zend_Form_Element_Submit('cancel');
        $cancel->setValue('Cancel');

        $this->addElements(array($name, $submit, $cancel));
    }
}