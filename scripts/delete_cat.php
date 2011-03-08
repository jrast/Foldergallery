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

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php')) {
// no module language file exists for the language set by the user, include default module language file DE.php
require_once(WB_PATH .'/modules/foldergallery/languages/DE.php');
} else {
// a module language file exists for the language defined by the user, load it
require_once(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php');
}

// Files includen
require_once (WB_PATH.'/modules/foldergallery/info.php');
require_once (WB_PATH.'/modules/foldergallery/scripts/backend.functions.php');

if(isset($_GET['page_id']) && is_numeric($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
}

if(isset($_GET['section_id']) && is_numeric($_GET['section_id'])){
	$section_id = $_GET['section_id'];
}



if(isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])) {
	$cat_id = $_GET['cat_id'];
	$sql = 'SELECT categorie, parent, has_child FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE id='.$cat_id.';';
	$query = $database->query($sql);
	if($result = $query->fetchRow()){
		// Dateien löschen
		$settings = getSettings($section_id);
		$delete_path = $path.$settings['root_dir'].$result['parent'].'/'.$result['categorie'];
		// DB Einträge löschen
		rek_db_delete($cat_id);
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
	} else {
		$admin->print_error($MOD_FOLDERGALLERY['ERROR_MESSAGE'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
	}

}
$admin->print_footer();
?>