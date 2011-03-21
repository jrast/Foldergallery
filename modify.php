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

// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: ../../index.php'));  


// check if backend.css file needs to be included into <body></body>
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/foldergallery/backend.css")) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/foldergallery/backend.css');
	echo "\n</style>\n";
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


// Einstellungen zur aktuellen Foldergallery aus der DB
$settings = getSettings($section_id);
// Falls noch keine Einstellungen gemacht wurden auf die Einstellungsseite umleiten
if($settings['root_dir'] == 'd41d8cd98f00b204e9800998ecf8427e') {
	?>
		<script language="javascript">
			function Weiterleitung() {
   				location.href= '<?php echo WB_URL; ?>/modules/foldergallery/modify_settings.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';
			}
			window.setTimeout("Weiterleitung()", 2000); // in msecs 1000 => eine Sekunde
		</script>
	<?php
	echo $MOD_FOLDERGALLERY['REDIRECT'];
} else {


// Template
$t = new Template(dirname(__FILE__).'/templates', 'remove');
$t->halt_on_error = 'no';
$t->set_file('modify', 'modify.htt');
// clear the comment-block, if present
$t->set_block('modify', 'CommentDoc'); $t->clear_var('CommentDoc');
$t->set_block('modify', 'ListElement', 'LISTELEMENT');
$t->clear_var('ListElement'); // Löschen, da dies über untenstehende Funktion erledigt wird.

// Links im Template setzen
$t->set_var(array(
	'SETTINGS_ONCLICK'  => 'javascript: window.location = \''.WB_URL.'/modules/foldergallery/modify_settings.php?page_id='.$page_id.'&amp;section_id='.$section_id.'\';',
	'SYNC_ONKLICK'      => 'javascript: window.location = \''.WB_URL.'/modules/foldergallery/sync.php?page_id='.$page_id.'&amp;section_id='.$section_id.'\';',
        'HELP_ONCLICK'      => 'javascript: window.location = \''.WB_URL.'/modules/foldergallery/help.php?page_id='.$page_id.'&amp;section_id='.$section_id.'\';',
	'EDIT_PAGE'         => $page_id,
	'EDIT_SECTION'      => $section_id,
	'WB_URL'            => WB_URL
));

// Text im Template setzten
$t->set_var(array(
	'TITEL_BACKEND_STRING' 	=> $MOD_FOLDERGALLERY['TITEL_BACKEND'],
	'TITEL_MODIFY' 		=> $MOD_FOLDERGALLERY['TITEL_MODIFY'],
	'SETTINGS_STRING' 	=> $MOD_FOLDERGALLERY['SETTINGS'],
	'FOLDER_IN_FS_STRING'	=> $MOD_FOLDERGALLERY['FOLDER_IN_FS'],
	'CAT_TITLE_STRING'	=> $MOD_FOLDERGALLERY['CAT_TITLE'],
	'ACTIONS_STRING'	=> $MOD_FOLDERGALLERY['ACTION'],
	'SYNC_STRING'		=> $MOD_FOLDERGALLERY['SYNC'],
	'EDIT_CSS_STRING'	=> $MOD_FOLDERGALLERY['EDIT_CSS'],
        'EXPAND_COLAPSE_STRING' => $MOD_FOLDERGALLERY['EXPAND_COLAPSE'],
        'HELP_STRING'           => $MOD_FOLDERGALLERY['HELP_INFORMATION']
));

// Template ausgeben
$t->pparse('output', 'modify');

// Kategorien von der obersten Ebene aus DB hohlen
$sql = "SELECT * FROM ".TABLE_PREFIX."mod_foldergallery_categories WHERE section_id=".$section_id." AND niveau=0;";
$query = $database->query($sql);
while($result = $query->fetchRow()){
	$results[] = $result;
}

function display_categories($parent_id, $section_id , $tiefe = 0) {
	$padding = $tiefe*20;
	global $database;
	global $url;
	global $page_id;
        global $MOD_FOLDERGALLERY;
	$list = "\n";
	$sql = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE parent_id='.$parent_id.' AND section_id ='.$section_id.' ORDER BY `position` ASC;';
	$query = $database->query($sql);
	$zagl = $query->numRows();
	
	
	$arrup = false;
	$arrdown = true;
	if ($zagl > 1) {}
	
	$counter = 0;	
	while($result = $query->fetchRow()){
		$counter ++;
		if ($counter > 1) {$arrup = true;}
		if ($counter == $zagl) {$arrdown = false;}
		
		if ($parent_id != "-1") $cursor = 'cursor: move;';
		else $cursor = '';

		if($result['has_child']){
			$list .= "<li id='recordsArray_".$result['id']."' style='padding: 1px 0px 1px 0px; ".$cursor."'>\n"
					."<table cellpadding='0' cellspacing='0' class='cat_table'>\n"
					.'<tr onmouseover="this.style.backgroundColor = \'#F1F8DD\';" onmouseout="this.style.backgroundColor = \'#ECF3F7\';">'
					."<td width='20px' style='padding-left:".$padding."px'>\n"
					// Pluszeichen Darsellen
					.'<a href="javascript: toggle_visibility(\'p'.$result['id'].'\');" title="'.$MOD_FOLDERGALLERY['EXPAND_COLAPSE'].'">'
					.'<img src="'.THEME_URL.'/images/plus_16.png" onclick="toggle_plus_minus(\''.$result['id'].'\');" name="plus_minus_'.$result['id'].'" border="0" alt="+" />'
					.'</a>'
					// Pluszeichen Ende
					."</td>\n"


					// Zeile Mit allen Angaben
					."<td width='".(300-$padding)."px'><a href='".$url['edit'].$result['id']."' title='".$MOD_FOLDERGALLERY['EDIT_CATEGORIE']."'>"
					.'<img src="'.THEME_URL.'/images/visible_16.png" alt="edit" border="0" align="left" style="margin-right: 5px" />'
					.htmlentities($result['categorie'])."</a></td>"
					."<td align='left'>".htmlentities($result['cat_name'])."</td>"
					
					//Active:
					.'<td width="30"><img src="'.WB_URL.'/modules/foldergallery/images/active'.$result['active'].'.gif" border="0" alt="" title="active" />&nbsp;&nbsp;</td>'
					
					
					// Aktionen Buttons
					."<td width='20'>";					
					if ($arrup == true) {$list .="<a href='".WB_URL."/modules/foldergallery/scripts/move_up.php?page_id=".$page_id."&section_id=".$section_id."&id=".$result['id']."' title='".$MOD_FOLDERGALLERY['MOVE_UP']."'>"
					."<img src='".THEME_URL."/images/up_16.png' border='0' alt='v' /></a>";
					}					
					$list .= "</td>"
					."<td width='20'>";
					
					if ($arrdown == true) {$list .="<a href='".WB_URL."/modules/foldergallery/scripts/move_down.php?page_id=".$page_id."&section_id=".$section_id."&id=".$result['id']."' title='".$MOD_FOLDERGALLERY['MOVE_DOWN']."'>"
					."<img src='".THEME_URL."/images/down_16.png' border='0' alt='u' />"
					."</a>";}
					
					$list .= "</td>";
					/* LÖSCHEN funktioniert ohnehin nicht wirklich, weil die Verzeichnisse beim Synchronisieren wieder auftauchen
					
					"<td width='20'>"
					."<a href='javascript: confirm_link(\"Sind sie sicher, dass Sie die ausgew&auml;hlte Kategorie mit allen Unterkategorien und Bilder l&ouml;schen m&ouml;chten?\", \"".WB_URL."/modules/foldergallery/scripts/delete_cat.php?page_id=".$page_id."&section_id=".$section_id."&cat_id=".$result['id']."\");' >"
					."<img src='".THEME_URL."/images/delete_16.png' border='0' alt='X'></a>"
					// Ende Zeile mit allen Angaben
					*/


					$list .= "</tr></table>\n"
					."<ul id='p".$result['id']."'style='padding: 1px 0px 1px 0px;' class='cat_subelem'>";
			$list .= display_categories($result['id'], $section_id, $tiefe+1);
			$list .= "</ul></li>\n ";
		} else {
			$list .= "<li id='recordsArray_".$result['id']."' style='padding: 1px 0px 1px 0px; ".$cursor."'>\n"
					."<table cellpadding='0' cellspacing='0' class='cat_table'>\n"
					.'<tr onmouseover="this.style.backgroundColor = \'#F1F8DD\';" onmouseout="this.style.backgroundColor = \'#ECF3F7\';">'
					."<td width='20px' style='padding-left:".$padding."px'></td>\n"
					// Zeile Mit allen Angaben
					."<td width='".(300-$padding)."px'><a href='".$url['edit'].$result['id']."' title='".$MOD_FOLDERGALLERY['EDIT_CATEGORIE']."'>"
					.'<img src="'.THEME_URL.'/images/visible_16.png" alt="edit" border="0" align="left" style="margin-right: 5px" />'
					.htmlentities($result['categorie'])."</a></td>"
					."<td align='left'>".htmlentities($result['cat_name'])."</td>"
					
					//Active:
					.'<td width="30"><img src="'.WB_URL.'/modules/foldergallery/images/active'.$result['active'].'.gif" border="0" alt="" title="active" />&nbsp;&nbsp;</td>'
					// Aktionen Buttons
					."<td width='20'>";					
					if ($arrup == true) {$list .="<a href='".WB_URL."/modules/foldergallery/scripts/move_up.php?page_id=".$page_id."&section_id=".$section_id."&id=".$result['id']."' title='".$MOD_FOLDERGALLERY['MOVE_UP']."'>"
					."<img src='".THEME_URL."/images/up_16.png' border='0' alt='v' /></a>";
					}					
					$list .= "</td>"
					."<td width='20'>";
					
					if ($arrdown == true) {$list .="<a href='".WB_URL."/modules/foldergallery/scripts/move_down.php?page_id=".$page_id."&section_id=".$section_id."&id=".$result['id']."' title='".$MOD_FOLDERGALLERY['MOVE_DOWN']."'>"
					."<img src='".THEME_URL."/images/down_16.png' border='0' alt='u' />"
					."</a>";}
					
					$list .= "</td>";
					/* LÖSCHEN funktioniert ohnehin nicht wirklich, weil die Verzeichnisse beim Synchronisieren wieder auftauchen
					
					."<td width='20'>"
					."<a href='javascript: confirm_link(\"Sind sie sicher, dass Sie die ausgew&auml;hlte Kategorie mit allen Unterkategorien und Bilder l&ouml;schen m&ouml;chten?\", \"".WB_URL."/modules/foldergallery/scripts/delete_cat.php?page_id=".$page_id."&section_id=".$section_id."&cat_id=".$result['id']."\");' >"
					."<img src='".THEME_URL."/images/delete_16.png' border='0' alt='X'></a>"
					// Ende Zeile mit allen Angaben
					*/
					$list .= "</tr></table>\n";
		}
	}	
	return $list;
}


$url = array(
	'edit'	=> WB_URL."/modules/foldergallery/modify_cat.php?page_id=".$page_id."&section_id=".$section_id."&cat_id=",
);

echo '<script type="text/javascript">
		var the_parent_id = "0";			
	</script>
	<ul>
		'.display_categories(-1, $section_id).'
	</ul>
<div id="dragableCategorie">
	<ul>
		'.display_categories(0, $section_id).'
	</ul>
</div>
<div style="display: block; width: 90%; height: 15px; padding: 5px;"><div id="dragableResult"> </div></div><hr>
';


// Schluss vom else Teil ganz oben!
}
?>