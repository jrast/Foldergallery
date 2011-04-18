<?php
/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

require('../../../config.php');
require(WB_PATH.'/modules/admin.php');

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file DE.php
	require_once(WB_PATH .'/modules/foldergallery/languages/DE.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php');
}

require_once(WB_PATH.'/modules/foldergallery/info.php');
require_once(WB_PATH.'/modules/foldergallery/admin/scripts/backend.functions.php');

$oldSettings = getSettings($section_id);
$newSettings = array();

//Daten aus $_post auswerten und validieren
if (isset($_POST['root_dir'])) {
    $newSettings['root_dir'] = $_POST['root_dir'];
} else {
    $newSettings['root_dir'] = '';
}
if (isset($_POST['extensions']) && ($_POST['extensions'] != '')) {
    $extensions = strtolower($_POST['extensions']);
	$extensionsarray = explode(',',str_replace(' ', '', $extensions));
	$extensionsarray = array_unique($extensionsarray);
	$newSettings['extensions'] = implode(',', $extensionsarray);
} else {
    $newSettings['extensions'] = '';
}
if (isset($_POST['invisible'])) {
	$newSettings['invisible'] = $_POST['invisible'];
} else {
	$newSettings['invisible'] = '';
}
if (isset($_POST['pics_pp']) && is_numeric($_POST['pics_pp']) ) {
	$newSettings['pics_pp'] = $_POST['pics_pp'];
} else {
	$newSettings['pics_pp'] = '';
}
if (isset($_POST['catpic']) && is_numeric($_POST['catpic']) ) {
	$newSettings['catpic'] = (int) $_POST['catpic'];
} else {
	$newSettings['catpic'] = 0;
}
if (isset($_POST['lightbox']) && file_exists( dirname(__FILE__).'/templates/view_'.$_POST['lightbox'].'.htt' ) ) {
	$newSettings['lightbox'] = $_POST['lightbox'];
// ----- jQueryAdmin / LibraryAdmin Integration; last edited 27.01.2011 -----
} elseif( isset($_POST['lightbox']) && file_exists( WB_PATH.'/modules/'.$_POST['lightbox'].'/foldergallery_template.htt' ) ) {
	$newSettings['lightbox'] = $_POST['lightbox'];
} elseif( isset($_POST['lightbox']) && file_exists( WB_PATH.'/modules/jqueryadmin/plugins/'.$_POST['lightbox'].'/foldergallery_template.htt' ) ) {
	$newSettings['lightbox'] = $_POST['lightbox'];
// ----- end jQueryAdmin / LibraryAdmin Integration -----
} else {
	$newSettings['lightbox'] = '';
}

// Get the new Thumbsettings:
if (isset($_POST['size_x']) && is_numeric($_POST['size_x']) ) {
	$newSettings['tbSettings']['image_x'] = (int) trim($_POST['size_x']);
} else {
	$newSettings['tbSettings']['image_x'] = 150;
}
if (isset($_POST['size_y']) && is_numeric($_POST['size_y']) ) {
	$newSettings['tbSettings']['image_y'] = (int) trim($_POST['size_y']);
} else {
	$newSettings['tbSettings']['image_y'] = 150;
}
if(isset($_POST['thumb_crop']) && is_string($_POST['thumb_crop']) && $_POST['thumb_crop'] == 'keep') {
    $newSettings['tbSettings']['image_ratio_fill'] = true;
} else {
    $newSettings['tbSettings']['image_ratio_fill'] = false;
}
// Fetch the advanced settings, they need a little bit more effort...
if(isset($_POST['thumb_advanced'])
   && is_string($_POST['thumb_advanced'])
   && $_POST['thumb_advanced'] != '')
{
    $advanced_settings = FG_setAdvancedThumbSettings($_POST['thumb_advanced']);
    $newSettings['tbSettings'] = array_merge($newSettings['tbSettings'], $advanced_settings);
}
// This is set by default as we want to resize the images
$newSettings['tbSettings']['image_resize'] = true;

echo "<div class=\"info\">".$MOD_FOLDERGALLERY['SAVE_SETTINGS']."</div><br />";
$newSettings['section_id'] = $section_id;

//SQL wich is used for all updates
$rawUpdtSQL = "UPDATE `".TABLE_PREFIX."mod_foldergallery_settings` SET `s_value` = '%s' WHERE "
    ."`section_id` = '".$section_id."' AND `s_name` = '%s';";
// SQL eintragen
$database->query(sprintf($rawUpdtSQL, $newSettings['root_dir'], 'root_dir'));
$database->query(sprintf($rawUpdtSQL, $newSettings['extensions'], 'extensions'));
$database->query(sprintf($rawUpdtSQL, $newSettings['invisible'], 'invisible'));
$database->query(sprintf($rawUpdtSQL, $newSettings['pics_pp'], 'pics_pp'));
$database->query(sprintf($rawUpdtSQL, $newSettings['catpic'], 'catpic'));
$database->query(sprintf($rawUpdtSQL, $newSettings['lightbox'], 'lightbox'));
$database->query(sprintf($rawUpdtSQL, serialize($newSettings['tbSettings']), 'tbSettings'));


if(( serialize($oldSettings['tbSettings']) != serialize($newSettings['tbSettings'])) && !isset($_POST['noNew'])){
	// Ok, thumb_size hat gewechselt, also alte Thumbs lÃ¶schen
	$sql = 'SELECT `parent`, `categorie` FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE section_id='.$oldSettings['section_id'].';';
	$query = $database->query($sql);
        echo '<div class="info">Loesche alte Thumbs:';
	while($link = $query->fetchRow()) {
		$pathToFolder = $path.$oldSettings['root_dir'].$link['parent'].'/'.$link['categorie'].$thumbdir;
		echo '<br/>Delete: '.$pathToFolder;
		deleteFolder($pathToFolder);
	}
	$pathToFolder = $path.$oldSettings['root_dir'].$thumbdir;
	echo '<br/>Delete: '.$pathToFolder.'</div>';
	deleteFolder($pathToFolder);

}

if($oldSettings['root_dir'] != $newSettings['root_dir']){

	// Und jetzt noch alte DB EintrÃ¤ge
	$sql = 'SELECT `parent`, `categorie` FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE section_id='.$oldSettings['section_id'].';';
	$query = $database->query($sql);
	while($cat = $query->fetchRow()) {
		$sql = 'DELETE FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE parent_id='.$cat['parent'];
		$database->query($sql);
	}


	$sql = 'DELETE FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE section_id='.$oldSettings['section_id'].';';
	$database->query($sql);
  // Root als Kategorie eintragen
  $sql = 'INSERT INTO '.TABLE_PREFIX."mod_foldergallery_categories ( `section_id`,`parent_id`,`categorie`,`parent`,`cat_name`,`active`,`is_empty`,`position`,`niveau`,`has_child`,`childs`,`description` )
    VALUES ( '$section_id', '-1', 'Root', '-1', 'Root', '1', '0', '0', '-1', '0', '', 'Root Description' );";
  $query = $database->query($sql);
  if($database->is_error()) {
  	$admin->print_error($database->get_error(), WB_URL.'/modules/foldergallery/admin/modify_settings.php?page_id='.$page_id.'&section_id='.$section_id);
  }
}

// Jetzt wird die DB neu synchronisiert //Anm CHio: Wozu? Wenn ein Fehler ist, kann man nichtmal die Settings speichern.
syncDB($newSettings);

// ÃœberprÃ¼fen ob ein Fehler aufgetreten ist, sonst Erfolg ausgeben
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/foldergallery/admin/modify_settings.php?page_id='.$page_id.'&section_id='.$section_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/foldergallery/admin/sync.php?page_id='.$page_id.'&section_id='.$section_id);
}

// Print admin footer
$admin->print_footer();
?>
