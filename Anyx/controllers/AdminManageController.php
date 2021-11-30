<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    
 * @copyright  Copyright 2010-2021 ITKAV Team
 * @license    
 * @version    $Id: AdminManageController.php 1010 2021-08-11 2:20:25Z reza $
 * @author     2RAD
 */

class Anyx_AdminManageController extends Core_Controller_Action_Admin {
    
     public function indexAction(){
          $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('anyx_admin_main', array(), 'anyx_admin_main_manage');
          if( !$this->_helper->requireUser()->isValid() ) return;

	     $this->view->myvar = "inja";
	
          // Prepare form
               $this->view->form = $form = new Anyx_Form_Admin_Global();
               $count = 0;

          // 	$this -> view ->assign("myvar","inja");
          //	return $this;
     }

     //connection controller
     public function connectionAction() {
          $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
               ->getNavigation('anyx_admin_main', array(), 'anyx_admin_main_connection');
          if( !$this->_helper->requireUser()->isValid() ) return;

          $this->view->connectionvar = "here is connection page";
          // Prepare form
          $this->view->form = $form = new Anyx_Form_Admin_Connection();

          
          $setting = Engine_Api::_()->getApi('settings', 'core');
          $formPopulateValues["host"]        = $setting->getSetting('anyx.server1.host');
          $formPopulateValues["dbname"]      = $setting->getSetting('anyx.server1.dbname');
          $formPopulateValues["username"]    = $setting->getSetting('anyx.server1.username');
          $formPopulateValues["password"]    = $setting->getSetting('anyx.server1.password');
          $formPopulateValues["port"]        = $setting->getSetting('anyx.server1.port');
          $formPopulateValues["socket"]      = $setting->getSetting('anyx.server1.socket');

          $form->populate( $formPopulateValues );

          // Check method/data validitiy
          if( !$this->getRequest()->isPost() ) {
               return;
          }

          if( !$form->isValid( $this->getRequest()->getPost() ) ) {
               return;
          }

          $values = $form->getValues();
          $test_connection = $this->getRequest()->getParam("test_connection");
          $save_Connection = $this->getRequest()->getParam("save");


      
          if ( isset( $test_connection ) ){
               // Check Connection
               if(  !empty( $values["host"] ) &&
                    !empty( $values["dbname"] ) &&
                    !empty( $values["username"] ) &&
                    !empty( $values["password"] )
               ) {
                    $result = $this->_checkConnection( $values );
                    if ( $result["status"] ){
                         $form->addNotice( $result["message"] );
                    } else {
                         $form->addError( $result["message"] ); 
                    }
               }
          } elseif ( isset ( $save_Connection ) ){
               $result = $this->_checkConnection( $values );
               if ( $result["status"] ){

                    $setting->setSetting('anyx.server1.host', $values["host"]);
                    $setting->setSetting('anyx.server1.username', $values["username"]);
                    $setting->setSetting('anyx.server1.password', $values["password"]);
                    $setting->setSetting('anyx.server1.dbname', $values["dbname"]);
                    $setting->setSetting('anyx.server1.port', $values["port"]);
                    // $setting->setSetting('anyx.server1.socket', $values["socket"]);
                    $setting->setSetting('anyx.server1.connection', true );
                    
                    $form->addNotice( "Your server information has been successfully saved.");

               } else {
                    $form->addError( $result["message"] );
                    $setting->setSetting('anyx.server1.connection', false );
               }
          }
   
	}

     //mapping controller
     public function mappingAction() {
          $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
               ->getNavigation('anyx_admin_main', array(), 'anyx_admin_main_mapping');
          if( !$this->_helper->requireUser()->isValid() ) return;

          // Prepare form
          $this->view->form = $form = new Anyx_Form_Admin_Mapping();
          $setting = Engine_Api::_()->getApi('settings', 'core');
          $connection = Engine_Api::_()->getApi('connection', 'anyx');
          $multiOptions = array(''=>'');
          foreach( $connection->getTables() as $tbl ){
               $multiOptions[$tbl] = $tbl;
          }
          $form->table_name->setMultiOptions( $multiOptions );
 	  //$formPopulateValues["table_name"] = $setting->getSetting('anyx.server1.tbsel');
	  $formPopulateValues["table_name"] = '';
          if ( in_array( $formPopulateValues["table_name"], $multiOptions ) ){
               $form->populate( $formPopulateValues );
          }

          // Check method/data validitiy
          if( !$this->getRequest()->isPost() ) {
               return;
          }

          if( !$form->isValid( $this->getRequest()->getPost() ) ) {
               return;
          }

          $values = $form->getValues();
	  $setting->setSetting('anyx.server1.tbsel', $values["table_name"] );
          $form->addNotice( "Your Table Selected has been successfully saved.");
	  $this->view->tableName = $values['table_name'];
	
	  
	}

     //event mapping controller
      public function mappingeventAction() {
          $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
               ->getNavigation('anyx_admin_main', array(), 'anyx_admin_main_mapping2');
          if( !$this->_helper->requireUser()->isValid() ) return;




	}

     //rythm controller
     public function rythmAction() {
          $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
               ->getNavigation('anyx_admin_main', array(), 'anyx_admin_main_rythm');
          if( !$this->_helper->requireUser()->isValid() ) return;

          // Prepare form
          $this->view->form = $form = new Anyx_Form_Admin_Rythm();
	  $setting = Engine_Api::_()->getApi('settings', 'core');
          $fields = array();
	  $connection = Engine_Api::_()->getApi('connection', 'anyx');
          $this->view->databaseName  = $setting->getSetting('anyx.server1.dbname');
          $this->view->tableName     = $setting->getSetting('anyx.server1.tbsel');
	  
	  foreach ( $connection->selectTable() as $key => $value ) {
               		$fields = $value;               		
			//$fields = array_keys($key);
			continue;
          		
	  }

          $this->view->tableFields =  $fields = array_keys($fields);
          $this->view->listofTableFields =  $fieldslist = array_keys($fields);
           
	  // Check method/data validitiy
           if( !$this->getRequest()->isPost() ) {
               return;
          }

          if( !$form->isValid( $this->getRequest()->getPost() ) ) {
               return;
          }          
          
	}

     public function mappingApprovalAction() {
          // In smoothbox
          $this->_helper->layout->setLayout('admin-simple');
          // Prepare form
          // mapping approval form
          $this->view->form = $form = new Anyx_Form_Admin_Mapapl();

          $setting = Engine_Api::_()->getApi('settings', 'core');
	  $counter = 1;
          if ( $fields = $setting->getSetting('anyx.server1.fields') ) {
              foreach ( $fields as $field => $value ) {
                    switch( $field ){
                         case "first@name":
                              $field = "first_name";
                         break;
     
                         case "last@name":
                              $field = "last_name";
                         break;
     
                         case "relational@id":
                              $field = "relational_id";
                         break;
                    }
                    $form->addElement('Dummy', "{$field}_{$value}", array(
                         'content' => "<div >{$field} => {$value}",
                         'order' => $counter++
                    ));
               }
          } else {
               return $this->_forward('success', 'utility', 'core', array(
                    'smoothboxClose' => true,
                    'parentRefresh' => true,
                    'messages' => array('You Must be mapping First!')
               ));
          }
       
           // Check method/data validitiy
           if( !$this->getRequest()->isPost() ) {
               return;
          }

          if( !$form->isValid( $this->getRequest()->getPost() ) ) {
               return;
          }
          
          $connection = Engine_Api::_()->getApi('connection', 'anyx');
          $settingTbl = Engine_Api::_()->getDbtable('settings', 'core');
          $rows = $connection->selectTable();

          $counter=0;
          foreach ( $rows  as $row ) {
               $params = array();
               foreach ( $row as $key => $value ) {
                    if ( $setting->getSetting('anyx.server1.fields.email') == $key )
                         $params ['email'] = $value;
                    elseif ( $setting->getSetting('anyx.server1.fields.first@name') == $key )
                         $params ['first_name'] = $value;
                    elseif ( $setting->getSetting('anyx.server1.fields.last@name') == $key )
                         $params ['last_name'] = $value;
                    elseif ( $setting->getSetting('anyx.server1.fields.relational@id') == $key )
                         $params ['relational_id'] = $value;
                    else
                         continue;
               }

               if ( $this->_signup( $params ) ){
                    $counter++;
               }
          }

          // done

          foreach( $setting->getSetting('anyx.server1.fields') as $field => $value ){
              $settingTbl->fetchRow( array( "name = ?" => "anyx.server1.fields.{$field}" )) ->delete();
          }

          return $this->_forward('success', 'utility', 'core', array(
               'smoothboxClose' => true,
               'messages' => array("{$counter} Users have been added Successfully.")
          ));
	}

     public function dragAction(){
          $container = $this->getRequest()->getParam( "container", null );

          $field_name = $this->getRequest()->getParam( "field",    null );
          $allowedTags = array(); // Allowed tags
          $allowedAttributes = array(); // Allowed attributes
          $stripTags = new Zend_Filter_StripTags($allowedTags,$allowedAttributes); // instance of zend filter
          $sanitizedInput = $stripTags->filter($field_name); //$input is input html

          $setting = Engine_Api::_()->getApi('settings', 'core');
          
          if ( $container == "email" || $container == "first@name" || $container == "last@name" || $container == "relational@id" ){
               $setting->setSetting("anyx.server1.fields.$container",$sanitizedInput);
          }
     }
     
     private function _checkConnection( $params = array() ) {
          try {
               // Autheticate input params
               if ( empty( $params["host"] ) &&
                    empty( $params["dbname"] ) &&
                    empty( $params["username"] ) &&
                    empty( $params["password"] ) ) { return array( "status" => false , "message" => "Invalid Credentials Data" ); }

                    $mysqli = null;
                    if ( !empty ( $params["port"] )  && !empty ( $params["socket"] ) ){
                        $mysqli = new mysqli( trim( $params["host"] ), trim( $params["username"] ), trim( $params["password"] ), trim( $params["dbname"] ), (int)trim( $params["port"] ), trim( $params["socket"] ) );
                    } elseif ( !empty ($params["port"] ) ){
                        $mysqli = new mysqli( trim( $params["host"] ), trim( $params["username"] ), trim( $params["password"] ), trim( $params["dbname"] ), (int)$params["port"] );
                    } else {
                        $mysqli = new mysqli( trim( $params["host"] ), trim( $params["username"] ), trim( $params["password"] ), trim( $params["dbname"] ) );
                    }
            
               // Check connection
               if ( $mysqli -> connect_errno) {
                 return array ("status" => false, "message" => "Failed to connect to MySQL: " . $mysqli -> connect_error );
               }   

               return array( "status" => true , "message" => "Communication with your server is established." ) ;
              
          } catch( Exception $ex ) {
              $result["message"] = Zend_Registry::get('Zend_Translate')->_( ucfirst( $ex->getMessage() ) );
              $result["status"] = false;
              return $result;
          }
     }

     private function _signup( $params ){
		
		$profileType = 1;
		$email = $password = $user_name = $first_name = $last_name = $relational_id= "";
		$passwordFlag = false;
		$user_table = Engine_Api::_ ()->getDbtable ( 'users', 'user' );
		$invited = false;

		// set default {
			if ( isset ( $params ['email'] )) {
				$email = $params ['email'];
			}
			
			if ( isset ( $params ['password'] )) {
				$password = $params ['password'];
			} else {
                    $password = Engine_Api::_()->user()->randomPass(10);
			}

			if ( isset ( $params ['first_name'] )) {
				$first_name = strtolower( $params ['first_name'] );
			}
			if ( isset ( $params ['last_name'] )) {
				$last_name = strtolower( $params ['last_name'] );
			}
			if ( isset ( $params ['relational_id'] )) {
				$relational_id = $params['relational_id'] ;
			}

			$params ['profileType'] = $profileType;

			if ( isset ( $params ['username'] )) {
				$user_name = strtolower( $params ['username'] );
			} else { // dont save username
				do {
					$user_name = $email;
				} while( null !== $user_table->fetchRow( array( 'username = ?' => $user_name ) ) );
			}
			
			$params["profileFields"]['1_1_3'] = $first_name;
			$params["profileFields"]['1_1_4'] = $last_name;
		// set default }

		// tables
		$user = $invite_user =  null;
		$new_user_id = null;
		$authorization_table = Engine_Api::_ ()->getDbtable ( 'levels', 'authorization' );
		$metatable = Engine_Api::_ ()->fields ()->getTable ( 'user', 'meta' );
		$userfieldvaluedtable = Engine_Api::_ ()->fields ()->getTable ( 'user', 'values' );
		$optiontable = Engine_Api::_ ()->fields ()->getTable ( 'user', 'options' );
		$userfieldsearchtable = Engine_Api::_ ()->fields ()->getTable ( 'user', 'search' );
		
		// check wheather user exist or not
		$settings = Engine_Api::_ ()->getApi ( 'settings', 'core' );
		if (! empty ( $email ) ) {
			$user_select = $user_table->fetchRow( array( 'email = ?' => $email ) );
			// If post exists
			if ( $user_select->extra_invites ){
				$invite_user = $user_select;
				$existing_user = '';
			} else {
				$existing_user = $user_select;
			}
		} else {
			$existing_user = '';
		}
		
		if ( !empty ( $user_name ) ) {
			// If post exists
			$existing_username = $user_table->fetchRow( array( 'username = ?' => str_replace ( ' ', '', $user_name ) ) );
		} else {
			$existing_username = '';
		}
		
		if ( empty ( $email ) || empty ( $password ) ) {
			$status = false;
			$message = 'All fields are compulsory( can not be blank ).';
               return false;
		} else if (! empty ( $existing_user )) {
			$status = false;
			$message = 'User with this email already exist.';
               return false;
		} else if (! empty ( $existing_username )) {
			$status = false;
			$message = 'User with this user name already exist.';
               return false;
		} else {
			try {
				// getting default values
				$settings = Engine_Api::_ ()->getApi ( 'settings', 'core' );
				
				// getting default level id
				
				$select_level = $authorization_table->select ()->where ( 'flag = ?', 'default' );
				$level = $authorization_table->fetchRow ( $select_level );
				
				$level_id = $level ['level_id'];
				$approved = ( int ) ($settings->getSetting ( 'user.signup.approve', 1 ) == 1);
				$verified = ( int ) ($settings->getSetting ( 'user.signup.verifyemail', 1 ) < 2);
				$enabled = ($approved && $verified);
				$timezone = $settings->getSetting ( 'core.locale.timezone', 'America/Los_Angeles' );
				$locale = $settings->getSetting ( 'core.locale.locale', 'auto' );
				$language = $settings->getSetting ( 'core.locale.language', 'en_US' );
				$emailfrom = $settings->getSetting ( 'core.mail.from');
				
				// apply md5 security on password
				$salt = ( string ) rand ( 1000000, 9999999 );
				$salt1 = Engine_Api::_ ()->getApi ( 'settings', 'core' )->getSetting ( 'core.secret', 'staticSalt' );
				$incrypt_password = md5 ( $salt1 . $password . $salt );

				$user_new_params = array (
					'user_id' => null,
					'email' => $email,
                         'relational_id' => $relational_id,
					'username' => $first_name ."_". $last_name,
					'displayname' => $first_name ." ". $last_name,
					'photo_id' => 0,
					'password' => $incrypt_password,
					'salt' => $salt,
					'locale' => $locale,
					'language' => $language,
					'timezone' => $timezone, 
					'search' => 1,
					'level_id' => $level_id,
					'enabled' => 1,
					'verified' => 1,
					'approved' => 1,
					'modified_date' => new Zend_Db_Expr ( 'NOW()' ),
					'creation_date' => new Zend_Db_Expr ( 'NOW()' ) 
				);

				if ( $invite_user ){
					$new_user_id = $invite_user->user_id;
					$user_new_params['user_id'] = $new_user_id;
					$user_table->update( $user_new_params, array( 'user_id = ?' => $invite_user->user_id ) );
				} else {
					$new_user_id = $user_table->insert ( $user_new_params );
				}
				
				$user = Engine_Api::_ ()->getItem ( 'user', $new_user_id );
				if( !$userfieldvaluedtable->fetchRow( array( 'item_id = ?' => $new_user_id ) ) ){
					$userfieldvaluedtable->insert ( array (
						'item_id' => $new_user_id,
						'field_id' => 1,
						'index' => 0,
						'value' => $profileType 
					) );
				}
				

				$values = Engine_Api::_ ()->getApi ( 'member', 'anyx' )->saveValues ( $params ['profileFields'], $user );
				
				// Send user an email
                    $mail_template = 'core_welcome_password';
                    $mailParams = array (
                         'pointmessage'=> "",
                         'host' => $_SERVER ['HTTP_HOST'],
                         'email' => $user->email,
                         'date' => time (),
                         'recipient_title' => $user_name,
                         'recipient_link' => $user->getHref (),
                         'recipient_photo' => $user->getPhotoUrl ( 'thumb.icon' ),
                         'queue' => false,
                         "password" => $password,
                    );

				$email_sent = Engine_Api::_ ()->getApi ( 'mail', 'core' )->sendSystem ( $user->email, $mail_template, $mailParams );
				
				Engine_Api::_ ()->getDbtable ( 'actions', 'activity' )->addActivity ( $user, $user, 'signup' );
				$status = true;
				$message = 'User has been created';
				// Increment signup counter
				Engine_Api::_ ()->getDbtable ( 'statistics', 'core' )->increment ( 'user.creations' );
			} catch ( Exception $e ) {
				$status = false;
				$message = 'User creation failed ' . $e;
                    throw $e;
                    return false;
			}
		}

		return true;
	}
     
}
?>