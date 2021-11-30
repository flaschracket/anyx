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
class Anyx_Form_Admin_Connection extends Engine_Form {
  public function init() {
    $this
      ->setTitle('Connection')
//      ->setDescription('setting the connection information to database or API');
      ->setDescription('setting the connection to mysql DB');

    // server
    $this->addElement('Text', 'host', array(
      'label' => 'Server Name/URL',
      'allowEmpty' => false,
      'required' => true,
      'validators' => array(
        array('NotEmpty', true),
        array('StringLength', false, array(1, 64)),
      ),
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));

    // port
    $this->addElement('Text', 'port', array(
      'label' => 'Port',
      'allowEmpty' => true,
      'required' => false,
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));

    // socket
    $this->addElement('Text', 'socket', array(
      'label' => 'socket',
      'allowEmpty' => true,
      'required' => false,
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));
    
    // DB Name
    $this->addElement('Text', 'dbname', array(
      'label' => 'Database Name',
      'allowEmpty' => false,
      'required' => true,
      'validators' => array(
        array('NotEmpty', true),
        array('StringLength', false, array(1, 64)),
      ),
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));
    
    // user Name
    $this->addElement('Text', 'username', array(
      'label' => 'User Name',
      'allowEmpty' => false,
      'required' => true,
      'validators' => array(
        array('NotEmpty', true),
      ),
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));

    // Password
    $this->addElement('Password', 'password', array(
      'label' => 'Password',
      'allowEmpty' => false,
      'required' => true,
      'validators' => array(
        array('NotEmpty', true),
      ),
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));

    // Test Connection
    $this->addElement('Button', 'test_connection', array(
      'label' => 'Test connection',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper')
    ));

    // Add submit button
    $this->addElement('Button', 'save', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper')
    ));

    $this->addDisplayGroup( array( 'test_connection' , 'save' ), 'buttons');
  }

}
