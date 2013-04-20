<?php

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
        $title = 'The form itself';
        $this->view->title = $title;

        $form = new Form_SubForm();
        $form->setAction($this->view->url(array('controller'=>'index', 'action'=>'subform')));
        $this->view->form = $form;
    }

    public function postDispatch()
    {
        if (isset($this->view->title)) {
            $this->view->headTitle($this->view->title, 'PREPEND');
        }
    }
}

