<?php
// Direkter Zugriff verhindern
if (!defined('WB_PATH')) die (header('Location: index.php'));

require_once (WB_PATH.'/modules/foldergallery/scripts/functions.php');

/**
 * Durchsucht einen Ordner rekursiv mit einigen Optionen
 * @return array
 * @param string $rootDir
 * @param array $allowedExtensions[optional]
 * @param array $invisibleFileNames[optional
 * @param integer $modus[optional]  0 = Files, 1 = Files/Folders, 2 = Folders
 * @param bool $rekursiv[optional] default = true
 * @param array $allData[optional]
 */

function scanDirectories($rootDir, $allowedExtensions = array (), $invisibleFileNames = array (),
$modus = 1, $rekursiv = true, $allData = array ()) {
	// run through content of root directory
	$dirContent = scandir($rootDir);
	foreach ($dirContent as $content) {
		// filter all files not accessible
		$path = $rootDir.'/'.$content;
		if (!in_array($content, $invisibleFileNames)) {
			// if content is file & readable, add to array
			if (is_file($path) && is_readable($path)) {
				$content_chunks = explode(".", $content);
				$ext = $content_chunks[count($content_chunks)-1];
				$ext = strtolower($ext);
				// only include files with desired extensions
				if (in_array($ext, $allowedExtensions)) {
					// save file name with path
					if ($modus < 2) {
						$allData[] = $path;
					}
				}
				// if content is a directory and readable, add path and name
			}
			elseif (is_dir($path) && is_readable($path)) {
				if ($modus > 0) {
					$allData[] = $path;
				}
				// recursive callback to open new directory
				if ($rekursiv) {
					$allData = scanDirectories($path, $allowedExtensions, $invisibleFileNames, $modus, $rekursiv, $allData);
				}
			}
		}
	}
	return $allData;
}

/**
 *
 * @return
 * @param string $rootDir
 * @param array $allowedExtensions
 * @param array $invisibleFileNames
 * @param integer $modus[optional]
 * @param bool $rekursiv[optional]
 */
function getFolderData($rootDir, $allowedExtensions, $invisibleFileNames, $modus = 1, $rekursiv = true) {
	$daten = array ();
	$daten = scanDirectories($rootDir, $allowedExtensions, $invisibleFileNames, $modus, $rekursiv);
	foreach ($daten as & $value) {
		$value = str_replace($rootDir, '', $value);
	}
	return $daten;
}

/**
 * Löscht das angegeben Verzeichnis und alle darin enthaltenen Unterverzeichnisse,
 * sowie die darin enthaltenen Files
 * @return int Fehlernummer
 * @param string $path Pfad zum Ornder
 */
function deleteFolder($path) {
	// schau' nach, ob das ueberhaupt ein Verzeichnis ist
	if (!is_dir($path)) {
		return -1;
	}
	// oeffne das Verzeichnis
	$dir = @opendir($path);

	// Fehler?
	if (!$dir) {
		return -2;
	}

	// gehe durch das Verzeichnis
	while (($entry = @readdir($dir)) !== false) {
		// wenn der Eintrag das aktuelle Verzeichnis oder das Elternverzeichnis
		// ist, ignoriere es
		if ($entry == '.' || $entry == '..')
			continue ;
		// wenn der Eintrag ein Verzeichnis ist, dann
		if (is_dir($path.'/'.$entry)) {
			// rufe mich selbst auf
			$res = deleteFolder($path.'/'.$entry);
		// wenn ein Fehler aufgetreten ist
		if ($res == -1) { // dies duerfte gar nicht passieren
			@closedir($dir); // Verzeichnis schliessen
		return -2; // normalen Fehler melden
}
else if ($res == -2) { // Fehler?
	@closedir($dir); // Verzeichnis schliessen
return -2; // Fehler weitergeben
}
else if ($res == -3) { // nicht unterstuetzer Dateityp?
	@closedir($dir); // Verzeichnis schliessen
return -3; // Fehler weitergeben
}
else if ($res != 0) { // das duerfe auch nicht passieren...
	@closedir($dir); // Verzeichnis schliessen
return -2; // Fehler zurueck
}
}
else if (is_file($path.'/'.$entry) || is_link($path.'/'.$entry)) {
	// ansonsten loesche diese Datei / diesen Link
	$res = @unlink($path.'/'.$entry);
// Fehler?
if (!$res) {
	@closedir($dir); // Verzeichnis schliessen
return -2; // melde ihn
}
}
else {
	// ein nicht unterstuetzer Dateityp
	@closedir($dir); // Verzeichnis schliessen
return -3; // tut mir schrecklich leid...
}
}
// schliesse nun das Verzeichnis
@closedir($dir);
// versuche nun, das Verzeichnis zu loeschen
$res = @rmdir($path);
// gab's einen Fehler?
if (!$res) {
	return -2; // melde ihn
}
// alles ok
return 0;
}

/**
 * Einfache Funktion zum ein File löschen
 * @return bool true wenn alles gut ging, sonst false
 * @param string $path pfad zum files
 */
function deleteFile($path) {
	if(is_file($path)){
		unlink($path);
		return true;
	} else {
		return false;
	}
}

/**
 * Syncronisiert eine gesamte Bildergalerie, löscht alte Einträge oder erstellt neu in der DB
 * Dabei wird das Dateisystem als Grundlage genommen. 
 * @return bool true = sucsess
 * @param array	$galerie  Einstellungen dieser Galerie
 * @param string $categorie ab welchem ordner gescannt werden soll, relativ zum Stammordner
 * @param integer $modus[optional] 0,1,2 Modus
 * @param bool $rekursiv[optional] soll rekursiv gesucht werden
 *
 * @see scanDirectories()
 * @see deleteFolder()
 */
function syncDB($galerie, $searchCategorie = '', $modus = 1, $rekursiv = true) {

	// Auf diese Variablen muss zugegriffen werden
	global $database;
	global $invisibleFileNames;
	global $url;
	global $path;
	global $thumbdir;
	
	
	// Daten Vorbereiten
	$rootDir = $path.$galerie['root_dir'];
	$searchFolder = $rootDir.$searchCategorie;
	$extensions = explode(',', $galerie['extensions']);
	$invisible = array_merge($invisibleFileNames, explode(',', $galerie['invisible']));

	//Alle Angaben aus dem Filesystem holen
	$allData = getFolderData($searchFolder, $extensions, $invisible);
	//natsort($allData); # ! Bringt es das?
	
	//Angaben auswerten
	$categories = array ();
	$files = array ();
	foreach ($allData as $data) {
		$einzelteile = explode('/', $data);
		$letztesElement = count($einzelteile)-1;
		if (substr_count($einzelteile[$letztesElement], '.') == 0) {
			//Hier werden alle Kategorien angelegt
			$catName = $einzelteile[$letztesElement];
			unset ($einzelteile[$letztesElement]);
			$catParents = implode('/', $einzelteile);
			$catParents = $searchCategorie.$catParents;
			$categories[] = array (
				'categorie'=>$catName,
				'parent'=>$catParents,
				'is_empty'=>1
			);
		} else {
			//Hier gehts um die Files
			$fileName = $einzelteile[$letztesElement];
			//if ($fileName == 'folderpreview.jpg') continue;
			
			unset ($einzelteile[$letztesElement]);
			$parent = implode('/', $einzelteile);
			$parent = $searchCategorie.$parent;			
			$fileLink = $url.$galerie['root_dir'].$parent."/".$fileName; 
			$fileLink = str_replace(WB_URL, '', $fileLink);
		
			$files[] = array (
				'file_name'=>$fileName,
				'file_link'=>$fileLink,				
				'parent'=>$parent
			);
		}
	}
	// Kategorien mit Bildern finden
	foreach ($categories as & $nameCat) {
		$catString = $nameCat['parent']."/".$nameCat['categorie'];
		foreach ($files as $file) {
			if ($file['parent'] == $catString) {
				$nameCat['is_empty'] = 0;
				break;
			}
		}
	}

	// Falls Parents, diese finden
	foreach ($categories as & $nameCat) {
		$catName = $nameCat['categorie'];
		foreach ($categories as $searchCat) {
			if ((strpos($searchCat['parent'], $catName) !== false)AND(!$searchCat['is_empty'])) {
				$nameCat['is_empty'] = 0;
				break;
			}
		}
	}
	
	// Kategorien mit DB synchronisieren
	// Neuer SQL vorbereiten
	$notDeleteArray = array();
	$insertSQL = "INSERT INTO ".TABLE_PREFIX."mod_foldergallery_categories (section_id, categorie, parent, cat_name, is_empty) VALUES";
	$deleteSQL = "DELETE FROM ".TABLE_PREFIX."mod_foldergallery_categories WHERE parent_id > '0' AND section_id=".$galerie['section_id'] ; 
	$deleteLaenge = strlen($deleteSQL);
	$insertLaenge = strlen($insertSQL);
	foreach ($categories as $cat) {
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_categories'
			.' WHERE section_id='.$galerie['section_id'].' AND'
			.' categorie="'.$cat['categorie'].'" AND'
			.' parent="'.$cat['parent'].'"'
			.' LIMIT 1;';
		$query = $database->query($sql);
		if($result = $query->fetchRow()) {
			if($result['is_empty'] == $cat['is_empty']){
				$notDeleteArray[] = $result['id'];
			} else {
				// Falls die Kategorie schon existierte nehmen wir für die neuen Einträge diejenigen von der DB
				$insertSQL .= " (".$result['section_id'].", '".$result['categorie']."', '".$result['parent']."', '".$result['cat_name']."', ".$cat['is_empty']."),";
				// Diese Datensätze müssen aber zuerst gelöscht werden, da sie sonst doppelt vorkommen würden!
			}
		} else {
			// Sonst erstellen wir einfach einen neuen Standarddatensatz
			$cat_name = str_replace('_', ' ',$cat['categorie']);
			$cat_name = str_replace('-', ' ',$cat_name);
			$insertSQL .= " (".$galerie['section_id'].", '".$cat['categorie']."', '".$cat['parent']."', '".$cat_name."', ".$cat['is_empty']."),";
		}
	}
	// SQL zum löschen der alten Einträge
	if(!empty($notDeleteArray)) {
		$deleteSQL .= ' AND (id NOT IN( '.implode(',',$notDeleteArray).'))';		
	}
	if($searchCategorie != ''){
		$deleteSQL.= ' AND (parent REGEXP("'.$searchCategorie.'"))';
	}
	if(strlen($deleteSQL) != $deleteLaenge){
		$deleteSQL .= ';';	
		$database->query($deleteSQL);
	}
	if(strlen($insertSQL) != $insertLaenge){
		// Jetzt fügen wir die neuen Einträge hinzu
		$insertSQL = substr($insertSQL, 0, -1).";";
		$database->query($insertSQL);
	}
	
	// So, dass waren die Kategorien, nun sind die Bilder an der Reihe
	
	//Die Felder "file_link" und "thumb_link" sind obsolet
	//Jetzt noch die Parents zu Ziffern umwandeln:
	
	//Wieder aus der Datenbank laden:	
	$catpathArray = array();
	$sql = 'SELECT id, categorie, parent FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE section_id='.$galerie['section_id'];
	$query = $database->query($sql);
	while($result = $query->fetchRow()) {		
		$p = $result['parent'].'/'.$result['categorie'] ;
		if ( $result['parent'] == -1) {$p = '';}
    	$catpathArray[$p] =  $result['id'];
    }
	
	
	$notDeleteArray = array();
	$insertSQL = "INSERT INTO ".TABLE_PREFIX."mod_foldergallery_files (file_name, parent_id, caption) VALUES";
	
	//-------------------------------------------------------------------------------------------------------
	//Das Macht ein Problem, sobald es mehrere Seiten mit FG gibt.
	//Das es keine Section_id mehr gibt, werden alle Einträge von anderen Sections ebenfalls gelöscht. 
	//Das muss anders gelöst werden, ich weiß aber nicht wie.
	$deleteSQL = "DELETE FROM ".TABLE_PREFIX."mod_foldergallery_files WHERE (id NOT IN";
	//Siehe unten, wird derzeit nicht ausgeführt
	//-------------------------------------------------------------------------------------------------------
	
	$laenge = strlen($insertSQL);
	$count = 0;
	foreach($files as $file) {		
		
		
		if (!isset($catpathArray[$file['parent']])) {
			$parent_id = 0;
			}else{
			$parent_id = $catpathArray[$file['parent']];
			//echo '|'.$file['parent'].'|';
			//var_dump($file);
			//die();
		}
	
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE parent_id ="'.$parent_id.'" AND file_name="'.$file['file_name'].'" LIMIT 1;';		
		$query = $database->query($sql);
		if($result = $query->fetchRow()){
			//$notDeleteArray[] = $result['id'];
		} else {
			$count ++;
			$insertSQL .= " ('".$file['file_name']."', '".$parent_id."', ''),";
			//if ($count == 2) echo $insertSQL;
		}
	}
	
	// SQL für neue Einträge	
	$insertSQL = substr($insertSQL, 0, -1).";";	
	if($laenge != strlen($insertSQL)) {
		echo "added ".$count." files";
		$database->query($insertSQL);
	}	
	delete_files_with_no_cat();
	return true;
}



function delete_files_with_no_cat() {
	global $database;
	$sql = 'SELECT id FROM '.TABLE_PREFIX.'mod_foldergallery_categories';
	$query = $database->query($sql);
	$notDeleteArray = array();
	while($result = $query->fetchRow()) {		
		$notDeleteArray[] = $result['id'];
    }
	if(!empty($notDeleteArray)) {
		$deleteSQL = "DELETE FROM ".TABLE_PREFIX."mod_foldergallery_files WHERE (parent_id NOT IN";
		$deleteSQL .= '(0,'.implode(',',$notDeleteArray).'));';
		$database->query($deleteSQL);		
	}
}


function rek_db_delete($cat_id) {
	global $database;
	
	$sql = 'SELECT section_id, categorie, parent, has_child FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE id='.$cat_id.';';
	$query = $database->query($sql);
	if($result = $query->fetchRow()) {
		$parent = $result['parent'].'/'.$result['categorie'];
		$delete_file_sql = 'DELETE FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE parent_id="'.$cat_id.'";';
		$database->query($delete_file_sql);
		if($result['has_child']){
			$select_sql = 'SELECT id FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE parent_id='.$cat_id.';';
			$query = $database->query($select_sql);
			while($select_result = $query->fetchRow()) {
				rek_db_delete($select_result['id']);
			}
		}
	}
	$deletesql = 'DELETE FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE id='.$cat_id;
	$database->query($deletesql);
}


?>
