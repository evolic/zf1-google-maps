<?php

/**
 * Controller class demonstrating usage of Zend_Form_SubForm
 *
 * @author    "Tomasz Kuter" <evolic@interia.pl>
 * @copyright 2013 Tomasz Kuter
 */
class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */

    }

    public function preDispatch()
    {
    }

    public function indexAction()
    {
        // action body
        $title = 'Homepage';
        $this->view->title = $title;
    }

    public function subformAction()
    {
        // action body
        $title = 'The SubForm example';
        $this->view->title = $title;

        $session = new Zend_Session_Namespace('form');
        if (!isset($session->products)) {
            $session->products = array('__template__', 'new');
        } else if ($this->getRequest()->isPost()) {
            $session->products = array_keys($_POST['products']);
        }

        $form = new Form_SubForm();
        $form->setAction($this->view->url(array('controller'=>'index', 'action'=>'subform')));

        $valid = true;
        $messages = array();
        $values = array();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $values = $form->getValues();
            } else {
                // form not valid
                $valid = false;
                $values = $form->getValues();
                $messages = $form->getMessages();
            }
        }

        $this->view->valid    = $valid;
        $this->view->form     = $form;
        $this->view->messages = $messages;
        $this->view->values   = $values;
    }

    public function creditsAction()
    {
        // action body
        $title = 'Credits';
        $this->view->title = $title;
    }

    public function sourceCodeAction()
    {
        // action body
        $title = 'Source code';
        $this->view->title = $title;
    }

    public function postDispatch()
    {
        if (isset($this->view->title)) {
            $this->view->headTitle($this->view->title, 'PREPEND');
        }
    }
}

