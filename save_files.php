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



if(!isset($_POST['save']) && !is_string($_POST['save'])) {
	echo "Falsche Formulardaten!";
} else {


	// Vorhandene POST Daten auswerten
	if(isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])) {
		$cat_id = $_GET['cat_id'];
	} else {
		$error['no_cat_id'] = 1;
		$admin->print_error('lost cat', ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
		die();
	}
	
	if(!isset($_POST['id'])) {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/foldergallery/modify_cat.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id);
		$admin->print_footer();
		die();
	}

	$anzahl = count($_POST['id']);
	$bilderNeu = array();
	for($i = 0; $i < $anzahl; $i++) {
		if(isset($_POST['caption'][$i]) && is_string($_POST['caption'][$i]) && ($_POST['caption'][$i] != '')) {
			$caption = htmlentities($_POST['caption'][$i],ENT_QUOTES,"UTF-8");
		} else {
			$caption = '';
		}
		$bilderNeu[] = array(
			'id'		=> $_POST['id'][$i],
			'caption' 	=> $caption,
			'delete' 	=> false
		);
		
	}	
	//echo '<pre>'; var_export($bilderNeu); echo '</pre>';
		
	// Jetzt machen wir alle Datenbank Änderungen
	$deleteSQL = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE ';
	$selectSQL = 'SELECT id, caption FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE ';
	$updateSQL = 'UPDATE '.TABLE_PREFIX.'mod_foldergallery_files SET ';
	foreach($bilderNeu as $bild){
		$selectArray[] = $bild['id'];
	}

	if(isset($selectArray)){
		$selectSQL .= '(id IN('.implode(',',$selectArray).'));';
		$query = $database->query($selectSQL);
		while($singleResult = $query->fetchRow()){
			foreach($bilderNeu as $bild) {
				if($bild['id'] == $singleResult['id']) {
					if($bild['caption'] != $singleResult['caption']){
						$updateArray[] = array(
							'id' => $bild['id'],
							'caption' => $bild['caption']
						);
					}
				}
			}
		}
		
	}
	
	if(isset($updateArray)) {
		$anzahlUpdates = count($updateArray);
		for($i = 0; $i < $anzahlUpdates; $i++) {
			$updateSQLNew = $updateSQL." caption='".$updateArray[$i]['caption']."' WHERE id=".$updateArray[$i]['id'].";";
			$database->query($updateSQLNew);
		}
	}
	
}
$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/foldergallery/modify_cat.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id);

$admin->print_footer();
?>