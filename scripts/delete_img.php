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



if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	$settings = getSettings($section_id);
	$root_dir = $settings['root_dir']; //Chio

	$cat_id = $_GET['cat_id'];
	$sql = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE id='.$_GET['id'].';';
	if($query = $database->query($sql)){
		$result = $query->fetchRow();
		$bildfilename = $result['file_name'];
		$parent_id = $result['parent_id'];
		//echo '<h2>'.$parent_id.'</h2>' ;
		
		$query2 = $database->query('SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE id='.$parent_id.' LIMIT 1;');
		$categorie = $query2->fetchRow();
		$parent   = $categorie['parent'].'/'.$categorie['categorie'];
		$folder = $root_dir.$parent;
		$pathToFolder = $path.$folder.'/';	

		
		$pathToFile = $path.$folder.'/'.$bildfilename;	
		$pathToThumb = $path.$folder.$thumbdir.'/thumb.'.$bildfilename;				
		deleteFile($pathToFile);
		deleteFile($pathToThumb);
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE id='.$_GET['id'];
		$database->query($sql);
			
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/foldergallery/modify_cat.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id);
		
		
	} else {
		$admin->print_error($MOD_FOLDERGALLERY['ERROR_MESSAGE'], WB_URL.'/modules/foldergallery/modify_cat.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id);
	}
} else {
	$admin->print_error($MOD_FOLDERGALLERY['ERROR_MESSAGE'], WB_URL.'/modules/foldergallery/modify_cat.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id);
}
$admin->print_footer();
?>