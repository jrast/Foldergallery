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
$pathToThumb = $path.$folder.$thumbdir.'/thumb.';
$urlToFolder = $url.$folder.'/';		
$urlToThumb = $url.$folder.$thumbdir.'/thumb.';

//echo '<h3>'.$pathToFolder.'</h3>'; 
echo '<h3><a href="modify_cat.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id.'">back</a></h3>';

$bilder= array();
$sql = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE parent_id="'.$parent_id.'" ORDER BY position ASC;';
$query = $database->query($sql);

echo '<script type="text/javascript">
			var the_parent_id = '.$parent_id.';			
			var WB_URL = "'.WB_URL.'";
			</script>
			';
			
echo '<div id="dragableTable"><ul>';
if($query->numRows()){
	while($result = $query->fetchRow()) {
	
	$bildfilename = $result['file_name'];
	$thumb = $pathToThumb.$bildfilename;	
		
	echo '<li id="recordsArray_'.$result['id'].'" style="width:'.$thumb_size.'px; height:'.$thumb_size.'px; "><table cellpadding="0" cellspacing="0" border="0" width="100%" class="sortgroup">
			<tr><td style="width:'.$thumb_size.'px; height:'.$thumb_size.'px;  "><img src="' . $urlToThumb.$bildfilename.'" title="'.$result['position'].': '.$bildfilename. '" /></td></tr>
			</table></li>
			';
	}
}
echo '</ul></div>';
echo '<div id="dragableResult" style="clear:left;">	<p>Reorder result will be displayed here.&nbsp; </p>		</div>';

$admin->print_footer();
?>

