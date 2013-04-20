<?php

/**
 * Class extending Zend Form functionality
 *
 * @author    "Tomasz Kuter" <evolic@interia.pl>
 * @copyright 2011-2013 Tomasz Kuter
 */
class App_Form_BaseForm extends Zend_Form
{
    const BUTTON_SUBMIT = 'submit';
    const BUTTON_CANCEL = 'cancel';

    const HIDDEN_HASH   = 'form';

    const DISPLAY_GROUP_BUTTONS = 'buttons';

    /**
     * Submit button
     * @var Zend_Form_Element_Submit
     */
    protected $submit;
    /**
     * Cancel button
     * @var Zend_Form_Element_Submit
     */
    protected $cancel;

    /**
     * Hidden element
     * @var Zend_Form_Element_Hidden
     */
    protected $hidden;

    /**
     * Form name
     * @var string
     */
    protected $formName;

    /**
     * Defines if form has file field
     * @var bool
     */
    protected $hasFileField = false;

    /**
     * @var bool
     */
    protected $hashed = false;

    protected $submitName = 'Submit';

    public function init()
    {
        parent::init();

        // add path to custom decorators
        $this->addPrefixPath('App_Form_Decorator', 'App/Form/Decorator', 'decorator');

        if ($this->hasFileField) {
            $this->setAttrib('enctype', 'multipart/form-data');
        }
    }

    protected function postSetup()
    {
        // create buttons
        $this->createButtons();

        // set element decorators
        $this->setupElementDecorators();

        // add hidden field with form name
        if ($this->hashed) {
            $this->setupHash();
        }

        // add buttons to form, set their decorators
        $this->setupButtons();
        // set form decorators
        $this->setupFormDecorators();
    }

    protected function createButtons()
    {
        $this->submit = new Zend_Form_Element_Submit(self::BUTTON_SUBMIT);
        $this->submit->setLabel($this->submitName)
            ->setAttrib('class', self::BUTTON_SUBMIT)
            ->setIgnore(true)
        ;

        $this->cancel = new Zend_Form_Element_Submit(self::BUTTON_CANCEL);
        $this->cancel->setLabel('Cancel')
            ->setAttrib('class', self::BUTTON_SUBMIT . ' ' . self::BUTTON_CANCEL)
            ->setIgnore(true)
        ;
    }

    protected function getButtonsDecorators()
    {
        $submitDecorators = array(
            'ViewHelper',
            array(
                array('data' => 'HtmlTag'),
                array('tag' =>'div', 'class'=> 'submit-element')
            ),
//            array(
//                array('row' => 'HtmlTag'),
//                array('tag' => 'li')
//            )
        );

        return $submitDecorators;
    }

    protected function addButtons()
    {
        $this->addElements(array(
            $this->submit,
            $this->cancel,
        ));
    }

    protected function setupButtons()
    {
        $this->addButtons();

        $submitDecorators = $this->getButtonsDecorators();
        $this->submit->setDecorators($submitDecorators);
        $this->cancel->setDecorators($submitDecorators);

        $this->addDisplayGroup(array(self::BUTTON_CANCEL, self::BUTTON_SUBMIT), self::DISPLAY_GROUP_BUTTONS);
        $dg = $this->getDisplayGroup(self::DISPLAY_GROUP_BUTTONS);
        $dg->removeDecorator('Fieldset');
        $dg->removeDecorator('HtmlTag');
        $dg->setDecorators(array(
            'FormElements',
//            'Fieldset',
            array('HtmlTag',array('tag'=>'li', 'class' => 'buttons'))
        ));
    }

    protected function setupElementDecorators()
    {
        $this->setElementDecorators($this->getElementDecorators());
    }

    protected function getElementDecorators()
    {
        $elementDecorators = array(
            'ViewHelper',
            array(
                array('data' => 'HtmlTag'),
                array('tag' =>'div', 'class'=> 'element')
            ),
            'Errors',
            array(
                'Label',
                array('tag' => 'div')
            ),
            array(
                array('row' => 'HtmlTag'),
                array('tag' => 'li')
            )
        );

        return $elementDecorators;
    }

    protected function getRichTextEditorDecorators()
    {
        Zend_Registry::get('log')->debug(__CLASS__ . '.' . __FUNCTION__);
        $elementDecorators = array(
            'ViewHelper',
            array(
                array('data' => 'HtmlTag'),
                array('tag' =>'div', 'class'=> 'element rich-element')
            ),
            'Errors',
            array(
                'Label',
                array('tag' => 'div')
            ),
            array(
                array('row' => 'HtmlTag'),
                array('tag' => 'li')
            )
        );

        return $elementDecorators;
    }

    protected function setupFormDecorators()
    {
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'ul'/*, 'data-role' => 'listview', 'role' => 'list'*/)),
            'Form'
        ));
    }

    protected function getSubFormDecorators()
    {
        return array(
            'FormElements',
            array('HtmlTag', array('tag' => 'ul'/*, 'data-role' => 'fieldset', 'role' => 'listitem'*/)),
            'Fieldset',
            array(
                array('row' => 'HtmlTag'),
                array('tag' => 'li', 'class' => 'subform')
            )
//             array('HtmlTag', array('tag' => 'li'/*, 'data-role' => 'listview', 'role' => 'list'*/))
        );
    }

    protected function setupHash()
    {
        $this->hidden = new Zend_Form_Element_Hidden(self::HIDDEN_HASH);
        $this->hidden->setValue($this->formName);

        $this->addElements(array(
            $this->hidden
        ));
        $this->hidden->setDecorators($this->getHiddenDecorators());
    }

    protected function getHiddenDecorators()
    {
        $hiddenDecorators = array(
            'ViewHelper',
            array(
                array('data' => 'HtmlTag'),
                array('tag' =>'div', 'class'=> 'hidden')
            ),
            array(
                array('row' => 'HtmlTag'),
                array('tag' => 'li')
            )
        );

        return $hiddenDecorators;
    }

    /**
     * Retrieve all form element values
     *
     * @param  bool $suppressArrayNotation
     * @return array
     */
    public function getValues($suppressArrayNotation = false)
    {
        $values = parent::getValues($suppressArrayNotation);

        foreach ($values as $key => $value) {
            if ($this->getElement($key) instanceof Zend_Form_Element_Submit) {
                unset($values[$key]);
            }
        }

        return $values;
    }

    public function checkCancel($post)
    {
        if (array_key_exists(self::BUTTON_CANCEL, $post)) {
            return true;
        }

        return false;
    }

    public function checkSubmit($post)
    {
        if (array_key_exists(self::BUTTON_SUBMIT, $post)) {
            return true;
        }

        return false;
    }


    public function verifySending($post)
    {
        if (!empty($post[self::HIDDEN_HASH]) && $post[self::HIDDEN_HASH] === $this->formName) {
            return true;
        }

        return false;
    }
}