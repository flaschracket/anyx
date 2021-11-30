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
class Anyx_Form_Admin_Global extends Engine_Form {
  public function init() {
    $this
      ->setTitle('Global Settings')
      ->setDescription('These settings affect all members in your community.');


    // $this->allow_profiles->getDecorator('Description')->setOption('placement', 'append');
    // $this->allow_profiles->getDecorator('Description')->setOption('escape', false);

    // Add submit button
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true
    ));

  }

}
