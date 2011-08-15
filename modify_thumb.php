<?php
require('../../config.php');
require(WB_PATH.'/modules/admin.php');

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

//get the CSS
// Very dirty version of including a file into the header!
echo '<link rel="stylesheet" type="text/css" href="'.WB_URL.'/modules/foldergallery/scripts/jcrob/css/jquery.Jcrop.css" /> ';


$cat_id = $_GET['cat_id'];

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	$settings = getSettings($section_id);
	$root_dir = $settings['root_dir']; //Chio
	
	$sql = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE id='.$_GET['id'].';';
	if($query = $database->query($sql)){
		$result = $query->fetchRow();
		$bildfilename = $result['file_name'];
		$parent_id = $result['parent_id'];
		
		
			$query2 = $database->query('SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE id='.$parent_id.' LIMIT 1;');
			$categorie = $query2->fetchRow();
		if ($categorie['parent'] != "-1") {
			$parent   = $categorie['parent'].'/'.$categorie['categorie'];
		}
		else $parent = '';
		
		$full_file_link = $url.$root_dir.$parent.'/'.$bildfilename;
		$full_file = $path.$root_dir.$parent.'/'.$bildfilename;
		$thumb_file = $path.$root_dir.$parent.$thumbdir.'/'.$bildfilename;
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{	
			//Löscht das bisherige Thumbnail
			deleteFile($thumb_file);
			
			//Neues Thumb erstellen
			if (generateThumb($full_file, $thumb_file, $settings['thumb_size'], 1, $settings['ratio'], $_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'])) {
				$admin->print_success('Thumb erfolgreich geändert', WB_URL.'/modules/foldergallery/modify_cat.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id);
			}
		}
		else {
			list($width, $height, $type, $attr) = getimagesize($full_file); //str_replace um auch Datein oder Ordner mit leerzeichen bearbeiten zu können.
			
			//erstellt ein passendes Vorschaufenster zum eingestellten Verhältniss
			if ($settings['ratio'] > 1) {
				$previewWidth = $settings['thumb_size'];
				$previewHeight = $settings['thumb_size'] / $settings['ratio'];
			}
			else {
				$previewWidth = $settings['thumb_size'] * $settings['ratio'];
				$previewHeight = $settings['thumb_size'];
			}
			
			echo '
			<!-- Gives the Jcrop the variable to work -->
			<script type="text/javascript">
				var relWidth = \''.$width.'\';
				var relHeight = \''.$height.'\';
				var thumbSize = \''.$settings['thumb_size'].'\';
				var settingsRatio = \''.$settings['ratio'].'\'
			</script>
			<h2>'.$MOD_FOLDERGALLERY['EDIT_THUMB'].'</h2>
			<p>'.$MOD_FOLDERGALLERY['EDIT_THUMB_DESCRIPTION'].'</p>
			<p>'.$full_file_link.'</p>
			<div style="float: left; padding: 0 20px 0 20px;">
				<img src="'.$full_file_link.'" id="cropbox" style="max-width: 500px; max-height: 500px;"/>
			</div>
			<div style="float:left;" align="center">
				<div style="overflow: hidden; width: '.$previewWidth.'px; height: '.$previewHeight.'px;">
					<img src="'.$full_file_link.'" id="preview">
				</div>
				<br />
				<!-- This is the form that our event handler fills -->
				<form action="'.WB_URL.'/modules/foldergallery/modify_thumb.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id.'&id='.$_GET['id'].'" method="post" onsubmit="return checkCoords();">
                                    	<input type="hidden" name="section_id" value="'.$section_id.'">
                                        <input type="hidden" name="page_id" value="'.$page_id.'">
					<input type="hidden" id="x" name="x" />
					<input type="hidden" id="y" name="y" />
					<input type="hidden" id="w" name="w" />
					<input type="hidden" id="h" name="h" />
					<input style="width: 130px;" type="submit" value="'.$MOD_FOLDERGALLERY['EDIT_THUMB_BUTTON'].'" /><br />
					<input style="width: 130px;" type="button" value="'.$TEXT['CANCEL'].'" onClick="parent.location=\''.WB_URL.'/modules/foldergallery/modify_cat.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id.'\'"/>
				</form>
			</div>';
		}
	}
}
else {
	$admin->print_error($MOD_FOLDERGALLERY['ERROR_MESSAGE'], WB_URL.'/modules/foldergallery/modify_cat.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id);
}

$admin->print_footer();

?>
