<?php
require('../../config.php');
if(defined('WB_PATH') == false) { exit("Cannot access this file directly");  }
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

$settings = getSettings($section_id);
$thumb_size = $settings['thumb_size']; //Chio
$root_dir = $settings['root_dir']; //Chio

if(isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])) {
	$cat_id = $_GET['cat_id'];
} else {
	$error['no_cat_id'] = 1;
}

// Kategorie Infos aus der DB holen
$sql = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE id='.$cat_id.' LIMIT 1;';
$query = $database->query($sql);
$categorie = $query->fetchRow();

if ( is_array( $categorie ) ) {
    if ( $categorie['parent'] != -1 ) {
        $cat_path = $path.$settings['root_dir'].$categorie['parent'].'/'.$categorie['categorie'];
        $parent   = $categorie['parent'].'/'.$categorie['categorie'];
    }
    else {
        // Root
        $cat_path = $path.$settings['root_dir'];
        $parent   = '';		
    }
}
$parent_id = $categorie['id'];
if ($categorie['active'] == 1) {$cat_active_checked = 'checked="checked"';} else {$cat_active_checked = '';}

$folder = $root_dir.$parent;
$pathToFolder = $path.$folder.'/';	
$pathToThumb = $path.$folder.$thumbdir.'/';
$urlToFolder = $url.$folder.'/';		
$urlToThumb = $url.$folder.$thumbdir.'/';


$bilder= array();
$sql = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE parent_id="'.$parent_id.'" ORDER BY position ASC;';
$query = $database->query($sql);


$t = new Template(dirname(__FILE__).'/templates', 'remove');
$t->set_file('modify_cat_sort', 'modify_cat_sort.htt');
// clear the comment-block, if present
$t->set_block('modify_cat_sort', 'CommentDoc');
$t->clear_var('CommentDoc');
// Get the Blocks
$t->set_block('modify_cat_sort', 'image_loop', 'IMAGE_LOOP');

// Replace Language Strings
$t->set_var(array(
	'REORDER_IMAGES_STRING' 	=> $MOD_FOLDERGALLERY['REORDER_IMAGES'],
	'CANCEL_STRING'                 => $TEXT['CANCEL'],
	'QUICK_SORT_STRING'		=> $MOD_FOLDERGALLERY['SORT_BY_NAME'],
	'QUICK_ASC_STRING'		=> $MOD_FOLDERGALLERY['SORT_BY_NAME_ASC'],
	'QUICK_DESC_STRING'		=> $MOD_FOLDERGALLERY['SORT_BY_NAME_DESC'],
	'MANUAL_SORT'			=> $MOD_FOLDERGALLERY['SORT_FREEHAND'],
        'FEEDBACK_MAN_SORT'             => $MOD_FOLDERGALLERY['REORDER_INFO_STRING'] 
));

// Links Parsen
$t->set_var(array(
	'CANCEL_ONCLICK'		=> 'javascript: window.location = \''.WB_URL.'/modules/foldergallery/modify_cat.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id.'\';',
	'QUICK_ASC_ONCLICK'		=> 'javascript: window.location = \''.WB_URL.'/modules/foldergallery/scripts/quick_img_sort.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id.'&sort=ASC\';',
	'QUICK_DESC_ONCLICK'	=> 'javascript: window.location = \''.WB_URL.'/modules/foldergallery/scripts/quick_img_sort.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id.'&sort=DESC\';'
));

// JS Werte Parsen
$t->set_var(array(
	'PARENT_ID_VALUE'		=> $parent_id,
	'WB_URL_VALUE'			=> WB_URL
));

// Bilder parsen
if($query->numRows()) {
	while($result = $query->fetchRow()) {
		$bildfilename = $result['file_name'];
		$thumb = $pathToThumb.$bildfilename;
		$t->set_var(array(
			'RESULT_ID_VALUE'		=> $result['id'],
			'THUMB_SIZE_VALUE'		=> $thumb_size,
			'THUMB_URL'				=> $urlToThumb.$bildfilename,
			'TITLE_VALUE'			=> $result['position'].': '.$bildfilename
		));
		$t->parse('IMAGE_LOOP', 'image_loop', true);
	}
}


$t->pparse('output', 'modify_cat_sort');

$admin->print_footer();
?>

