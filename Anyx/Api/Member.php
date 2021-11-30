<?php
class Anyx_Api_Member extends Core_Api_Abstract {
	protected $_elements = array ();

	
	// block members
	public function getBlockMembers($params) {
		$viewer = Engine_Api::_()->user()->getViewer();
		
		if($viewer->getIdentity()){
			$user_id = $viewer->getIdentity();
		}
		elseif(isset($params['userId'])){
			$user_id = $params ['userId'];
		}else{
			$user_id = 0;
		}
		
		$offset = 0;
		$page_id = 1;
		$item_per_page = 10;
		if (isset ( $params ['offset'] )) {
			$offset = $params ['offset'];
		}
		if (isset ( $params ['pageId'] )) {
			$page_id = $params ['pageId'];
		}
		if (isset ( $params ['itemPerPage'] )) {
			$item_per_page = $params ['itemPerPage'];
		}
		if ($page_id == 1 || $page_id == '') {
			$offset = 0;
		}
	
		$log = Engine_Api::_ ()->getApi ( 'core', 'socialapi' )->getLog ();
	
		$user_table = Engine_Api::_ ()->getDbtable ( 'users', 'user' );
		$userInfo = $user_table->info ( 'name' );
	
		$blockTable = Engine_Api::_ ()->getDbtable ( 'block', 'user' );
		$blockTableName = $blockTable->info('name');
		
		$storage_table = Engine_Api::_ ()->getDbtable ( 'files', 'storage' );
		$storageInfo = $storage_table->info ( 'name' );
	
		$blockSelect = $blockTable->select()
									->setIntegrityCheck ( false )
									->from ( array ('block' => $blockTableName), array())
									->join ( array ('users' => $userInfo), 'block.blocked_user_id=users.user_id', array ('users.displayname','users.user_id','users.status'))
									->joinLeft ( array ('storage' => $storageInfo), "users.photo_id=storage.parent_file_id and storage.type = 'thumb.icon'", array ('storage_path' => 'storage.storage_path'))
									->where ( 'block.user_id =?', $user_id )
									->group ( 'users.user_id' );

		 $result = $blockTable->fetchAll ($blockSelect);
		
		 $blockCount = count ( $result );
		 
		 $total = $blockCount / $item_per_page;
		 $total_pages = '';
		 if (is_float ( $total )) {
		 	$round = round ( $total );
		 	if ($round > $total) {
		 		$total_pages = $round;
		 	} else {
		 		$total_pages = $round + 1;
		 	}
		 } else {
		 	$total_pages = $total;
		 }
		 
		$blockSelect->limit($item_per_page, $offset);
		$userblocks = $blockTable->fetchAll ($blockSelect);
		$blockerArray = array ();
		
		$sSiteUrl = Engine_Api::_ ()->getApi ( 'core', 'socialapi' )->getUrl();
		foreach ( $userblocks as $blockuser ) {
			$blockData = array ();
				
			$blockData ['userId'] = $blockuser->user_id;
			$blockData ['status'] = $blockuser->status;
			if (! empty ( $blockuser ['storage_path'] )) {
				$blockData ['thumbnail'] = $sSiteUrl . "/" . $blockuser->storage_path;
			} else {
				$blockData ['thumbnail'] = $sSiteUrl . "/application/modules/User/externals/images/nophoto_user_thumb_profile.png";
			}
				
			$blockData ['username'] = $blockuser->displayname;
				
			array_push ( $blockerArray, $blockData );
		}
		
		if(!$total_pages){
			$total_pages = 0;
		}
		
		$blockerList = array (
				'totalPageCount' => $total_pages,
				'pageId' => ( int ) $page_id,
				'itemPerPage' => ( int ) $item_per_page,
				'offset' => $offset,
				'blockmembers' => $blockerArray,
				'status' => true
		);
	
		return $blockerList;
	}

	public function getProfileFields($params) {
		$profile = $params ['profileType'];
		$user_id = $params ['userId'];
		if (empty ( $profile ) || empty ( $user_id )) {
			return array (
					'status' => false,
					'message' => 'check parameters' 
			);
		}
		
		$metatable = Engine_Api::_ ()->fields ()->getTable ( 'user', 'meta' );
		$metaInfo = $metatable->info ( 'name' );
		$optiontable = Engine_Api::_ ()->fields ()->getTable ( 'user', 'options' );
		$optionsInfo = $optiontable->info ( 'name' );
		$mapstable = Engine_Api::_ ()->fields ()->getTable ( 'user', 'maps' );
		$mapInfo = $mapstable->info ( 'name' );
		$valuetable = Engine_Api::_ ()->fields ()->getTable ( 'user', 'values' );
		$valueTableName = $valuetable->info ( 'name' );
		$select = $metatable->select ()->setIntegrityCheck ( false )->from ( array (
				'meta' => $metaInfo 
		), array (
				'meta.field_id',
				'meta.type',
				'meta.label',
				'meta.required' 
		) )->joinLeft ( array (
				'maps' => $mapInfo 
		), 'meta.field_id =maps.child_id', array () )->joinLeft ( array (
				'values' => $valueTableName 
		), 'meta.field_id=values.field_id and item_id =' . $user_id, array (
				'value' 
		) )->where ( 'maps.field_id = ?', 1 )->where ( 'maps.option_id =?', $profile )->order ( 'maps.order' );
		
		$fields = $metatable->fetchAll ( $select );
		
		$this->socialapi_log = Engine_Api::_ ()->getApi ( 'core', 'socialapi' )->getLog ();
		$this->socialapi_log->log ( 'select query > ' . $select, Zend_Log::DEBUG );
		$struct = array ();
		
		foreach ( $fields as $field ) {
			$data = array ();
			$fieldoption = array ();
			$option = array ();
			unset ( $option );
			// $this->socialapi_log->log('field_id > '.$field->field_id,Zend_Log::DEBUG);
			$data ['fieldId'] = '1_' . $profile . '_' . $field->field_id;
			$data ['type'] = $field->type;
			$data ['value'] = $field->value;
			$data ['label'] = $field->label;
			$data ['required'] = $field->required;
			$optiontable = Engine_Api::_ ()->fields ()->getTable ( 'user', 'options' );
			$select = $optiontable->select ()->where ( 'field_id = ?', $field->field_id );
			$options = $optiontable->fetchAll ( $select );
			
			foreach ( $options as $fieldop ) {
				
				$option ['optionId'] = $fieldop->option_id;
				$option ['optionLable'] = $fieldop->label;
				array_push ( $fieldoption, $option );
			}
			
			$data ['options'] = $fieldoption;
			
			// }
			
			array_push ( $struct, $data );
		}
		
		return array (
				'status' => true,
				'profileFields' => $struct 
		);
	}
	public function getElement($name) {
		if (array_key_exists ( $name, $this->_elements )) {
			return $this->_elements [$name];
		}
		return null;
	}
	public function saveValues($fields, $user) {

		if(is_array($fields)){
			$fVals = $fields;
		}else{
			$fVals = json_decode ( $fields );
		}

		$user_id = $user->getIdentity ();
		$values = Engine_Api::_ ()->fields ()->getFieldsValues ( $user );
		$privacyOptions = Fields_Api_Core::getFieldPrivacyOptions ();

		foreach ( $fVals as $key => $value ) {
			$parts = explode ( '_', $key );
			if (count ( $parts ) != 3)
				continue;
			list ( $parent_id, $option_id, $field_id ) = $parts;
			
			// Whoops no headings
			if ($this->getElement ( $key ) instanceof Engine_Form_Element_Heading) {
				continue;
			}
			
			// // Array mode
			if (is_array ( $value )) {
				
				// Lookup
				$valueRows = $values->getRowsMatching ( array (
						'field_id' => $field_id,
						'item_id' => $user_id 
				) );
				
				// Insert all
				$indexIndex = 0;
				if (is_array ( $value ) || ! empty ( $value )) {
					foreach ( ( array ) $value as $singleValue ) {
						$valueRow = $values->createRow ();
						$valueRow->field_id = $field_id;
						$valueRow->item_id = $user_id;
						$valueRow->index = $indexIndex ++;
						$valueRow->value = $singleValue;
						
						$valueRow->save ();
					}
				} else {
					$valueRow = $values->createRow ();
					$valueRow->field_id = $field_id;
					$valueRow->item_id = $user_id;
					$valueRow->index = 0;
					$valueRow->value = '';
					
					$valueRow->save ();
				}
			} 			

			// Scalar mode
			else {
				// Lookup
				$valueRow = $values->getRowMatching ( array (
						'field_id' => $field_id,
						'item_id' => $user_id,
						'index' => 0 
				) );
				
				// Create if missing
				$isNew = false;
				if (! $valueRow) {
					$isNew = true;
					$valueRow = $values->createRow ();
					$valueRow->field_id = $field_id;
					$valueRow->item_id = $user_id;
				}
				
				$valueRow->value = htmlspecialchars ( $value );
				
				$valueRow->save ();
			}
		}
		
		// Update search table
		Engine_Api::_ ()->getApi ( 'core', 'fields' )->updateSearch ( $user, $values );
		
		// Fire on save hook
		Engine_Hooks_Dispatcher::getInstance ()->callEvent ( 'onFieldsValuesSave', array (
				'item' => $user,
				'values' => $values 
		) );

		// Update display name
		$aliasValues = Engine_Api::_ ()->fields ()->getFieldsValuesByAlias ( $user );
		$user->setDisplayName ( $aliasValues );
		// $user->modified_date = date('Y-m-d H:i:s');
		$user->save ();
		
		// update networks
		Engine_Api::_ ()->network ()->recalculate ( $user );
		return true;
	}

	public function removePhoto() {
		$user = Engine_Api::_ ()->user ()->getViewer ();
		$user->photo_id = 0;
		$user->save ();
		
		$status = true;
		$message = Zend_Registry::get ( 'Zend_Translate' )->_ ( 'Your photo has been removed.' );
		
		return array (
				'status' => $status,
				'message' => $message 
		);
	}
}

