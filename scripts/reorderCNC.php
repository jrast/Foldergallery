<?php
require('../../../config.php');
if(defined('WB_PATH') == false) { exit("Cannot access this file directly");  }

// First we prevent direct access and check for variables
if(!isset($_POST['action']) OR !isset($_POST['recordsArray'])) {
	// now we redirect to index, if you are in subfolder use ../index.php
	header( 'Location: ../../index.php' ) ;
} else {

	

	// check if user has permissions to access the  module
	require_once(WB_PATH.'/framework/class.admin.php');
	$admin = new admin('Modules', 'module_view', false, false);
	if (!($admin->is_authenticated() && $admin->get_permission('foldergallery', 'module'))) 
		die(header('Location: ../../index.php'));
	

	// Sanitized variables
	$action = $admin->add_slashes($_POST['action']);
	$updateRecordsArray = isset($_POST['recordsArray']) ? $_POST['recordsArray'] : array();


	 
// This line verifies that in &action is not other text than "updateRecordsListings", if something else is inputed (to try to HACK the DB), there will be no DB access..
	if ($action == "updateRecordsListings"){
	 
		$listingCounter = 1;
		$output = "";
		foreach ($updateRecordsArray as $recordIDValue) {
			
			//$database->query("UPDATE `".TABLE_PREFIX."mod_members` SET `position` = ".$listingCounter." WHERE `member_id` = ".$recordIDValue." AND `group_id` = ".$group_id." ");
			$database->query("UPDATE `".TABLE_PREFIX."mod_foldergallery_categories` SET position = ".$listingCounter." WHERE `id` = ".$recordIDValue);
			//$output .= "p".$recordIDValue. ' p'.$listingCounter. ' g'."\n";


			$listingCounter ++;
		}
	 
		echo '<img src="'.WB_URL.'/modules/jsadmin/images/success.gif" style="vertical-align:middle;"/> <span style="font-size: 80%">Sucessfully reorderd</span>';

	}
} // this ends else statement from the top of the page
?>