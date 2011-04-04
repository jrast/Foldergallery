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

require('../../config.php');
require(WB_PATH.'/modules/admin.php');

// Direkten Zugriff verhindern
if (!defined('WB_PATH')) die (header('Location: index.php'));

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file DE.php
	require_once(WB_PATH .'/modules/foldergallery/languages/DE.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php');
}

require_once(WB_PATH.'/modules/foldergallery/info.php');
require_once(WB_PATH.'/modules/foldergallery/scripts/backend.functions.php');

$settings = getSettings($section_id);

$flag = false;

/* syncDB($galerie) ist kompletter updatealgorithmus */
if(syncDB($settings)) {

	echo "<div class=\"info\">".$MOD_FOLDERGALLERY['SYNC_DATABASE']."</div><br />";

	// Wieder alle Angaben aus der DB holen um Sortierung festzulegen
	$results = array();
	$sql = "SELECT * FROM ".TABLE_PREFIX."mod_foldergallery_categories WHERE section_id =".$section_id;
	$query = $database->query($sql);
	
	if ( $query->numRows() > 0 ) {
		
    	while($result = $query->fetchRow()) {
			
			$folder = $settings['root_dir'].'/'.$result['parent'].'/'.$result['categorie'];
			$pathToFolder = $path.$folder;
			if ($result['parent'] != -1) {; //nicht die roots;
				//checken, ob es das Verzeichnis noch gibt:
				if(!is_dir($pathToFolder)){
					$delete_sql = 'DELETE FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE id="'.$result['id'].'";';
					$database->query($delete_sql);
					//echo '<p>DELETE: '.$pathToFolder. '</p>';
					continue;
				}
			}
		
    		$results[] = $result;
    	}

    	$niveau = 0;
    	// Alle Kategorien durchlaufen zum Kinder und Parents und Level zuzuordnen
    	foreach($results as &$cat) {
    		$cat['niveau'] = substr_count($cat['parent'],'/');
    		if($cat['niveau'] > $niveau){
    			$niveau = $cat['niveau'];
    		}
    		// String bilden für Parentvergleich
    		$ast = $cat['parent']."/".$cat['categorie'];
			$cat['ast'] = $ast;
			$cat['childs'] = '';
    		// Alle Kategorien durchlaufen und auf gleichheit untersuchen
    		foreach($results as &$searchcat){
    			if($ast == $searchcat['parent']) {
    				// Falls gleich, kann bestimmt werden wer Kind und welcher Parent ist
    				$cat['has_child'] = 1;					
    				$searchcat['parent_id'] = $cat['id'];
    			}
    		}
    	}
		
		//Das ginge sicher besser:
		//Childs finden
		foreach($results as &$cat) {		
			if ($cat['has_child'] == 0) continue;
			foreach($results as $others) {
				if ($cat['id'] == $others['id']) continue;
				
				if  (strpos($others['ast'], $cat['ast']) !== false) {
					//others ist also ein Child von $cat
					$cat['childs'].= ','.$others['id'];
				}			
			}
		}
		//-------------------------

    	// Sortierung festlegen
    	foreach($results as &$cat) {
    		if($cat['position'] == 0) {
    			$last = 0;
    			foreach($results as $vergleich) {
    				if($cat['parent'] == $vergleich['parent']){
    					if($last <= $vergleich['position']) {
    						$last = $vergleich['position'];
    					}
    				}
    			}
    			$cat['position'] = $last+1;
    		}
    	}

    	// Datenkank Update
    	$updatesql = 'UPDATE '.TABLE_PREFIX.'mod_foldergallery_categories SET ';
    	for($i = 0; $i<count($results); $i++){
			$childs = $results[$i]['childs'];
			//$childs=substr($childs,1,strlen($childs-1)); //Führenden Beistrich belassen, der wird in view wieder benotigt
    		$sql = $updatesql." niveau=".$results[$i]['niveau'].", parent_id=".$results[$i]['parent_id'].", has_child=".$results[$i]['has_child'].", position=".$results[$i]['position'].", childs='".$childs."' WHERE id=".$results[$i]['id'].";";
    		if($database->query($sql)){
    			$flag = true;
    		} else {
    			break;
    		}
    	}

    	// Fehler/Lücken in der Sortierung beheben
    	for($i = 0; $i<=$niveau; $i++) {
    		$last_parent = 0;
    		$counter = 1;
    		$sql = "SELECT `position`,`id`, `parent_id` FROM ".TABLE_PREFIX."mod_foldergallery_categories WHERE section_id =".$section_id." AND niveau=".$i." ORDER BY position ASC, parent_id ASC;";
    		$query = $database->query($sql);
    		while($result = $query->fetchRow()){
    			if($last_parent == $result['parent_id']) {
    				if($counter != $result['position']){
    					$sql = $updatesql." `position`=".$counter." WHERE id=".$result['id'].";";
    					$database->query($sql);
    				}
    				$counter++;
    			} else {
    				$last_parent = $result['parent_id'];
    				$counter = 1;
    				if($counter != $result['position']){
    					$sql = $updatesql." `position`=".$counter." WHERE id=".$result['id'].";";
    					$database->query($sql);
    				}
    				$counter++;
    			}
    		}
    	}

      if($flag) {
      	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
      } else {
    	  $admin->print_error("Synchronisation fehlgeschlagen", WB_URL.'/modules/foldergallery/modify_settings.php?page_id='.$page_id.'&section_id='.$section_id);
      }

    }   // keine Kategorien vorhanden
    else {
        $admin->print_error( $MOD_FOLDERGALLERY['NO_CATEGORIES'], WB_URL.'/modules/foldergallery/modify_settings.php?page_id='.$page_id.'&section_id='.$section_id );
    }

}

// Print admin footer
$admin->print_footer();
?>