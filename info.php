<?php
/**
  This module is free software. You can redistribute it and/or modify it under
  the terms of the GNU General Public License - version 2 or later, as published
  by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  This module is distributed in the hope that it will be useful, but
  WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
  GNU General Public License for more details.

 -------------------------------------------------------------------------------
  Modul: foldergallery für Website Baker v2.7 (http://www.websitebaker.org)
  Modulbeschreibung: Eine einfache Bildergalerie erstellen anhand der
  Ordnerstruktur auf dem Server. Im Backend kann zu jedem Bild/jeder Kategorie
  eine Beschreibung angegeben werden.
 -------------------------------------------------------------------------------
 *
 * The Changelog of this Module can be found in the README.markdown file!

  V1.21 (Juerg Rast)
  + fiexed a lot of bugs
  + repaired libraryAdmin integration
 -------------------------------------------------------------------------------
**/


$module_directory 	= 'foldergallery';
$module_name 		= 'Foldergallery';
$module_function 	= 'page';
$module_version 	= '1.30';
$module_platform 	= '2.80';	

$module_author 		= 'J&uuml;rg Rast; schliffer; Bianka Martinovic; Chio; Pumpi';
$module_license 	= 'GNU General Public License';
$module_description     = 'Bildergalerie anhand der Ordnerstruktur erstellen';
$module_home		= 'http://www.foldergallery.ch/';
$module_guid		= 'C716E2D6-10C2-4D20-97AC-EFF428EADCAE';

/**
 *  Pfad und URL zum Stammverzeichnis der Foldergallery
 *  Das Stammverzeichnis ist das h�chste Verzeichnis
 *  auf welches die Foldergallery zugriff hat.
 *  Die Werte m�ssen auf das gleiche Verzeichnis zeigen.
 *  Diese Verzeichnisse k�nnen sie nat�rlich �ndern!
 *  (z.B) f�r externe Ordner
**/

$path = WB_PATH; // Vorher WB_PATH.MEDIA_DIRECTORY;
$url = WB_URL; // Vorher: WB_URL.MEDIA_DIRECTORY;
$thumbdir = '/fg-thumbs';
// Des gleiche wie oben, aber ohne Slash
// Wird für die Suche benötigt
$thumbdir1 = 'fg-thumbs'; 
$pages = substr(PAGES_DIRECTORY, 1);

/**
 * Diese Zeilen nur ändern wenn du genau weisst was du tust! 
 * '.' und '..' dürfen nicht entfernt werden!
 * Weitere invisibleFileNames können direkt im Backend der Foldergallery definiert werden.
 */

//Alle Ordner ausschliessen, welche zum Core von WB gehoeren
$wbCoreFolders = array('account','admin','framework','include','languages','modules',$pages,'search','temp','templates');
$invisibleFileNames = array('.', '..', $thumbdir1);

$megapixel_limit = 5; //Ab dieser gr��e wird kein Thumb mehr erzeugt.

?>
