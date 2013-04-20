<?php

/**
 * Class demonstrating usage of Zend_Form_SubForm
 *
 * @author    "Tomasz Kuter" <evolic@interia.pl>
 * @copyright 2013 Tomasz Kuter
 */
class Form_SubForm extends App_Form_BaseForm
{
    /**
     * @var bool
     */
    protected $hashed = true;

    /**
     * Form name
     * @var string
     */
    protected $formName = 'subform';

    protected $submitName = 'Save';


    public function init()
    {
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('stringTrim')
            ->setAttrib('class', 'long-200')
        ;

        $town = new Zend_Form_Element_Text('town');
        $town->setLabel('Town')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('stringTrim')
            ->setAttrib('class', 'long-200')
        ;

        $postcode = new Zend_Form_Element_Text('postcode');
        $postcode->setLabel('Postcode')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('stringTrim')
            ->setAttrib('class', 'long-50')
        ;

        $address = new Zend_Form_Element_Text('address');
        $address->setLabel('Address')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('stringTrim')
            ->setAttrib('class', 'long-200')
        ;

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('E-mail')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('stringTrim')
            ->addValidator('EmailAddress', false, array('domain' => false))
            ->setAttrib('class', 'long-200')
        ;

        $order_date = new Zend_Form_Element_Text('Date');
        $order_date->setLabel('Date')
            ->setValue(date('Y-m-d'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('stringTrim')
            ->addValidator('Date', false, array('format' => 'Y-m-d'))
        ;

        $options = array(
            1 => 'Domestic Standard',
            2 => 'Domestic Expedited',
            3 => '2-Day Domestic',
            4 => '1-Day Domestic',
        );
        $shipping = new Zend_Form_Element_Radio('shipping');
        $shipping->setLabel('Shipping')
            ->setRequired(true)
            ->addMultiOptions($options)
            ->setSeparator('')
            ->addValidator('inArray', false, array(
                'haystack' => array_keys($options),
                'messages' => array(
                    Zend_Validate_InArray::NOT_IN_ARRAY => 'Selected valid shipping option'
                )
            ))
        ;

        $subForm = new Zend_Form_SubForm();
        $subForm->setName('products');

        // setup validators
        $price_validator = new Zend_Validate_Between(array('min' => 0, 'max' => 1000000));
        $price_validator->setInclusive(false)
            ->setMessages(array(
                Zend_Validate_Between::NOT_BETWEEN_STRICT => "'%value%' is not valid price",
            ))
        ;
        $quantity_validator = new Zend_Validate_Between(array('min' => 1, 'max' => 1000000));
        $quantity_validator->setMessages(array(
            Zend_Validate_Between::NOT_BETWEEN => "'%value%' is not valid quantity",
        ))
        ;

        // get session
        $session = new Zend_Session_Namespace('form');

        foreach ($session->products as $product) {
            $rowForm = new Zend_Form_SubForm();
            $rowForm->setName($product);

            if ($product === '__template__') {
                $rowForm->setAttrib('style', 'display: none;');
            }

            $product_name = new Zend_Form_Element_Text('name');
            $product_name
                ->setLabel('Product')
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('class', 'product-name');
            ;
            if ($product !== '__template__') {
                $product_name->setRequired(true);
            }

            $product_price = new Zend_Form_Element_Text('price');
            $product_price
                ->setLabel('Price in PLN')
                ->setAttrib('class', 'number price long-100')
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
            ;
            if ($product !== '__template__') {
                $product_price->setRequired(true)
                    ->addValidator('Float', false, array('locale' => 'en_GB'))
                    ->addValidator($price_validator)
                ;
            }

            $product_quantity = new Zend_Form_Element_Text('quantity');
            $product_quantity
                ->setLabel('Quantity')
                ->setAttrib('class', 'number quantity long-150')
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->addFilter('Int')
            ;
            if ($product !== '__template__') {
                $product_quantity->setRequired(true)
                    ->addValidator($quantity_validator)
                ;
            }

            $product_remove = new Zend_Form_Element_Button('remove');
            $product_remove->setLabel('Remove')
                ->setAttrib('class', 'remove')
                ->setAttrib('onclick', 'removeProduct(this)')
            ;

            $elements = array($product_name, $product_price, $product_quantity, $product_remove);
            foreach ($elements as $element) {
                if ($product !== '__template__' && $element->getName() !== 'remove') {
                    $element->setRequired(true);
                }
            }
            $rowForm->addElements($elements);
            $rowForm->setElementDecorators($this->getElementDecorators());

            $rowForm->getElement('remove')->removeDecorator('Label');

            $rowForm->setDecorators($this->getSubFormDecorators());
            $subForm->addSubForm($rowForm, $product);
        }

        $this->addElements(array($name, $order_date, $town, $postcode, $address, $email, $shipping));
        $subForm->setDecorators($this->getSubFormDecorators());

        $product_add = new Zend_Form_Element_Button('add');
        $product_add->setLabel('Add product')
            ->setAttrib('class', 'add')
        ;
        $subForm->addElement($product_add);

        $subForm->setElementDecorators($this->getElementDecorators());
        $subForm->getElement('add')->removeDecorator('Label');
        $this->addSubForm($subForm, 'products');

//         $submit = new Zend_Form_Element_Submit('submit');
//         $submit->setValue('Save');
//         $cancel = new Zend_Form_Element_Submit('cancel');
//         $cancel->setValue('Cancel');

//         $this->addElements(array($submit, $cancel));

        // add buttons, setup decorators, etc
        $this->postSetup();
    }

    protected function getElementDecorators()
    {
        $elementDecorators = array(
            'ViewHelper',
            'Errors',
            array(
                'Label',
                array('class' => 'lab')
            ),
            array(
                array('row' => 'HtmlTag'),
                array('tag' => 'li'/*, 'data-role' => 'fieldcontain', 'role' => 'listitem'*/),
            )
        );

        return $elementDecorators;
    }

//     protected function getHiddenDecorators()
//     {
//         $hiddenDecorators = array(
//             'ViewHelper',
//         );

//         return $hiddenDecorators;
//     }
}