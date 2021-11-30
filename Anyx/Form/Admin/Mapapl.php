<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    
 * @copyright  Copyright 
 * @license    
 * @version    $Id: Global.php 1010 2021-08-11 2:20:25Z reza $
 * @author     
 */
class Anyx_Form_Admin_Mapapl extends Engine_Form {
  public function init() {

    $this->setTitle('Mapping Approval')
      ->setDescription('Please read below fields and Approve this, if its right! OK? Please consider after approval we make a user with the mapping data, So It\'s So Important.')
      ->setAttrib('class', 'global_form_popup')
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
      ->setMethod('POST');
      ;

    //$this->addElement('Hash', 'token');

    // Buttons
    $this->addElement('Button', 'submit', array(
        'label' => 'Genarate Users',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper'),
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'prependText' => ' or ',
        'href' => '',
        'onclick' => 'parent.location.reload();',
        'decorators' => array(
            'ViewHelper'
        ),
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons',array("order"=>1000));
  }

}
