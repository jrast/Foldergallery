<?php
require('../../../config.php');
require(WB_PATH.'/modules/admin.php');
	
// check if backend.css file needs to be included into <body></body>
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/foldergallery/backend.css")) {
echo '<style type="text/css">';
include(WB_PATH .'/modules/foldergallery/backend.css');
echo "\n</style>\n";
}
// check if backend.js file needs to be included into <body></body>
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/foldergaller/backend.js")) {
echo '<script type="text/javascript">';
include(WB_PATH .'/modules/foldergallery/backend.js');
echo "</script>";
}

// include the default language
require_once(WB_PATH .'/modules/foldergallery/languages/EN.php');
// check if module language file exists for the language set by the user (e.g. DE, EN)
if(file_exists(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php')) {
    require_once(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php');
}

// Files includen
require_once (WB_PATH.'/modules/foldergallery/info.php');
require_once (WB_PATH.'/modules/foldergallery/admin/scripts/backend.functions.php');

$settings = getSettings($section_id);

if(isset($_POST['save'])) {
	if(isset($_POST['cat_id'])) {
		$cat_id = $_POST['cat_id'];
	} else {
		$error['no_cat_id'] = 1;
	}
	if(isset($_POST['cat_name'])) {
		$cat_name = $_POST['cat_name'];
	}
	if(isset($_POST['cat_description'])) {
		$cat_description = $_POST['cat_description'];
	}
	
	$active = 0;
	if(isset($_POST['active'])) {
		$active = $_POST['active'];
	}
	$sql = 'UPDATE '.TABLE_PREFIX.'mod_foldergallery_categories SET cat_name="'.$cat_name.'", description="'.$cat_description.'", active="'.$active.'" WHERE id='.$cat_id;
	if($database->query($sql)){
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
	} else {
		$admin->print_error($MOD_FOLDERGALLERY['ERROR_MESSAGE'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
	}
}

$admin->print_footer();
?>