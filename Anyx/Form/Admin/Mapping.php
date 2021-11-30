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
class Anyx_Form_Admin_Mapping extends Engine_Form {
  public function init() {
    $this
      ->setTitle('Table Name')
      ->setDescription('Select table name you want to connect with.');
/*samira addElement('select' to 'multiCheckbox' */
//	  $this->addElement('multiCheckbox', 'table_name', array(
	  $this->addElement('Select', 'table_name', array(
		'label' => 'Table Name:',
		'multiOptions' => array(
			'' => '',
		),
	));

    // Add submit button
    $this->addElement('Button', 'save', array(
		'label' => 'Save Changes',
		'type' => 'submit',
		'ignore' => true
    ));

  }

}
