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

//Modul Description
$module_description = 'Erstellen sie eine vollautomatische Bildergalerie mit Ordner als Kategorien';

//Variables for the Frontend
$MOD_FOLDERGALLERY['VIEW_TITLE']		= 'Bildergalerie';
$MOD_FOLDERGALLERY['CATEGORIES_TITLE']	= 'Kategorien';
$MOD_FOLDERGALLERY['BACK_STRING']		= 'Zur &Uuml;bersicht';
$MOD_FOLDERGALLERY['FRONT_END_ERROR']	= 'Diese Kategorie existiert nicht oder enth&auml;lt keine Bilder und Unterkategorien!';
$MOD_FOLDERGALLERY['PAGE']            = 'Seite';




//Variables for the Backend
$MOD_FOLDERGALLERY['PICS_PP'] = 'Bilder pro Seite';
$MOD_FOLDERGALLERY['LIGHTBOX'] = 'Lightbox';

$MOD_FOLDERGALLERY['MODIFY_CAT_TITLE']		= 'Kategorie und Bilddetailes bearbeiten';
$MOD_FOLDERGALLERY['MODIFY_CAT']			= 'Kategoriedetailes bearbeiten:';
$MOD_FOLDERGALLERY['CAT_NAME']				= 'Kategoriename/Titel:';
$MOD_FOLDERGALLERY['CAT_DESCRIPTION']		= 'Kategoriebeschrieb:';
$MOD_FOLDERGALLERY['MODIFY_IMG']			= 'Bilder bearbeiten:';
$MOD_FOLDERGALLERY['IMAGE']					= 'Bild';
$MOD_FOLDERGALLERY['IMAGE_NAME']			= 'Bildname';
$MOD_FOLDERGALLERY['IMG_CAPTION']			= 'Bildbeschrieb';


$MOD_FOLDERGALLERY['REDIRECT']  			= 'Sie m&uuml;ssen zuerst die Grundeinstellungen vornehmen.'
											. ' Sie werden in zwei Sekunden weitergeleitet! (Funktioniert nur wenn JavaScript aktiviert!';
$MOD_FOLDERGALLERY['TITEL_BACKEND'] 			= 'Foldergallery Verwaltung';
$MOD_FOLDERGALLERY['TITEL_MODIFY'] 			= 'Kategorien und Bilder bearbeiten:';
$MOD_FOLDERGALLERY['SETTINGS'] 				= 'Allgemeine Einstellungen';
$MOD_FOLDERGALLERY['ROOT_DIR'] 				= 'Stammverzeichnis';
$MOD_FOLDERGALLERY['EXTENSIONS']			= 'Erlaubte Dateien';
$MOD_FOLDERGALLERY['INVISIBLE']				= 'Unsichtbare Ordner';
$MOD_FOLDERGALLERY['NEW_SCANN_INFO']			= 'Durch diese Aktion wurden erst die Datenbankeintr&auml;ge erstellt. Die Vorschaubilder werden automatisch beim ersten Aufruf der Kategorie erzeugt!';
$MOD_FOLDERGALLERY['FOLDER_NAME']			= 'Ordnername im Dateisystem';
$MOD_FOLDERGALLERY['DELETE']				= 'L&ouml;schen?';
$MOD_FOLDERGALLERY['ERROR_MESSAGE']			= 'Keine Daten zum verarbeiten Erhalten!';
$MOD_FOLDERGALLERY['DB_ERROR']				= 'Datenbank Fehler!';
$MOD_FOLDERGALLERY['FS_ERROR']				= 'Fehler beim l&ouml;schen des Ordners!';
$MOD_FOLDERGALLERY['NO_FILES_IN_CAT']			= 'Diese Kategorie enth&auml;lt keine Bilder!';
$MOD_FOLDERGALLERY['SYNC']				= 'Datenbank mit Filesystem synchronisieren';
$MOD_FOLDERGALLERY['EDIT_CSS']				= 'CSS bearbeiten';
$MOD_FOLDERGALLERY['FOLDER_IN_FS']			= 'Ordner im Dateisystem:';
$MOD_FOLDERGALLERY['CAT_TITLE']				= 'Kategorietitel:';
$MOD_FOLDERGALLERY['ACTION']				= 'Aktionen:';
$MOD_FOLDERGALLERY['NO_CATEGORIES'] 			= 'Keine Kategorien (=Unterverzeichnisse) vorhanden.<br /><br />Die Galerie funktioniert trotzdem, zeigt aber keine Kategorien an.';
$MOD_FOLDERGALLERY['EDIT_THUMB'] 			= 'Thumbnail bearbeiten';
$MOD_FOLDERGALLERY['EDIT_THUMB_DESCRIPTION']		= '<strong>Bitte neuen Bildausschnitt wählen</strong>';
$MOD_FOLDERGALLERY['EDIT_THUMB_BUTTON']			= 'Thumbnail erstellen';
$MOD_FOLDERGALLERY['THUMB_SIZE']			= 'Thumbnail Größe';
$MOD_FOLDERGALLERY['THUMB_RATIO']			= 'Thumbnail Verhältniss';
$MOD_FOLDERGALLERY['THUMB_NOT_NEW']			= 'Keine neuen Thumbnails erzeugen';
$MOD_FOLDERGALLERY['CHANGING_INFO']			= 'Das ändern von <strong>Thumbnail Größe</strong> oder <strong>Thumbnail Verhältniss</strong> bewirkt das löschen (und neu erstellen) aller Thumbnails.';
$MOD_FOLDERGALLERY['SYNC_DATABASE']			= 'Synchronisiere Dateisystem mit Datenbank...';
$MOD_FOLDERGALLERY['SAVE_SETTINGS']			= 'Einstellungen werden gespeichert...';
$MOD_FOLDERGALLERY['SORT_IMAGE']			= 'Bilder sortieren';
$MOD_FOLDERGALLERY['BACK']				= 'Zur&uuml;ck';
$MOD_FOLDERGALLERY['REORDER_INFO_STRING']               = 'Status: Ordnen sie die Elemente nun neu an.';
$MOD_FOLDERGALLERY['REORDER_INFO_SUCESS']               = 'Status: Die neue Anordnung wurde erfolgreich gespeichert!';
$MOD_FOLDERGALLERY['REORDER_IMAGES']                    = 'Bilder sortieren';
$MOD_FOLDERGALLERY['SORT_BY_NAME']                      = 'Bilder nach Dateiname sortiern';
$MOD_FOLDERGALLERY['SORT_BY_NAME_ASC']                  = 'Dateiname aufsteigend';
$MOD_FOLDERGALLERY['SORT_BY_NAME_DESC']                 = 'Dateiname absteigend';
$MOD_FOLDERGALLERY['SORT_FREEHAND']                     = 'Frei sortieren (Drag & Drop)';
$MOD_FOLDERGALLERY['THUMB_EDIT_ALT']                    = 'Vorschaubild bearbeiten';
$MOD_FOLDERGALLERY['IMAGE_DELETE_ALT']                  = 'Bild löschen';


// Tooltips
$MOD_FOLDERGALLERY['ROOT_FOLDER_STRING_TT']	= 'Dieser Ordner legt den Stammordner fest, in welchem rekursiv nach Bilder gesucht wird. Bitte nur beim installieren &auml;ndern, sonst gehen alle Infos zu den Bilder verloren!';
$MOD_FOLDERGALLERY['EXTENSIONS_STRING_TT']	= 'Legen sie hier die erlaubten Dateierweiterungen fest. Verwenden sie das Koma als Trennzeichen. Auf Gross-/Kleinschreibung wird nicht geachtet.';
$MOD_FOLDERGALLERY['INVISIBLE_STRING_TT']	= 'Ordner die sie hier eintragen werden nicht durchsucht.';
$MOD_FOLDERGALLERY['DELETE_TITLE_TT']		= 'Achtung, es werden ALLE Bilder und Unterkategorien mitsamt den Bilder vom Server gelöscht!';
?>
