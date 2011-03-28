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
  Modul: foldergallery f�r Website Baker v2.7 (http://www.websitebaker.org)
  Modulbeschreibung: Eine einfache Bildergalerie erstellen anhand der
  Ordnerstruktur auf dem Server. Im Backend kann zu jedem Bild/jeder Kategorie
  eine Beschreibung angegeben werden.
 -------------------------------------------------------------------------------
   v0.1  (J�rg Rast; 12.2.2009)
    + erstes Release des Moduls
    
   v0.11 (J�rg Rast; 12.2.2009)
    + Fehler im Frontend behoben
	+ in allen Ordner eine index.php hinzugef�gt

   v0.12 (J�rg Rast; 14.2.2009)
	+ Frontend verbessert (Fehlermeldung bei nicht vorhandnen Kategorien)
	+ Backend:
		+ Neu scannen wird jetzt automatisch erledigt (in "allgemeine Einstellungen")
		+ add.php: Initialwerte hinzugef�gt und direkte weiterleitung auf Einstellungen
	+ shutter in scripts Ordner verschoben
	+ Allen Ordner eine index.php verpasst
	
   v0.12 (J�rg Rast; 16.2.2009)
	+ in der view.php rontend.css �berpr�fung hinzugef�gt.
	+ im backend funktion "alle Ordner neu scannen" entfernt.

   v0.13 (J�rg Rast; 18.2.2009)
    + Designfehler behoben
    
   v0.2 (J�rg Rast; 6.5.2009)
    + Datenbankstruktur angepasst
    + Kategorie beschreibung hinzugef�gt
    + Sortierung von Kategorien m�glich
    + Backend erneuert
    + Filesystem <-> DB Synchronisierung verbessert 

	v0.3  (schliffer; 17.10.2009)
	+ Sortierung der Kategorien angepasst
	+ L�schen von Ordner auf Laufwerk verhindern
	+ Thumbnails ohne weissen Hintergrund erstellen
	
	v0.4 (Bianka Martinovic; 26.10.2009)
	+ Korrektur: Das Rootverzeichnis wird nun auch als Kategorie verwaltet
    + Kategorietitel = Seiten�berschrift, Default: "Bildergalerie" (aus Sprachfile)
    + erm�glicht die Verwaltung der Bilder im Rootverzeichnis
    
  v0.5 (Bianka Martinovic; 27.10.2009)
  + Anzahl der Bilder pro Seite im Backend einstellbar (Default: 15)

  v0.6 (Bianka Martinovic; 27.10.2009)
  + Unterst�tzung "Highslide"
  
  v0.7 (Bianka Martinovic; 27.10.2009)
  + Unterst�tzung "NyroModal"
  
  v0.8 (Bianka Martinovic; 28.10.2009)
  + englisches Sprachmodul
  + Korrektur: Einstellung f�r "unsichtbare" Verzeichnisse wurde beim Syncen
               nicht ber�cksichtigt
  + Korrektur: Klasse f�r NyroModal (nyroModal statt nyromodal)
  
  v0.9 (Bianka Martinovic; 28.01.2010)
  + Lightbox jetzt im Backend ausw�hlbar
  + Breadcrumb in Unterkategorien
  + Korrektur: Paging (es kamen nicht die Bilder, die man erwartet hat)
  + CSS aufbereitet
  
  v0.9c (Chio, Juni 2010)
  Kreuz und quer durch alle Scripts.

  v1.01 (Pumpi, Juli 2010)
  + Templateverarbeitung umgestellt um leichter weitere Lightboxes einzuf�gen
  + Anpassung der Thumberstellung um ein Ordentliches Erscheinungsbild im Frontent zu bieten.
  + �ndern der Thumb und Orner�bersicht

  v1.02 (Pumpi und Webbird, Juni 2010)
  + Erweiterung der Templates um zusammenarbeit mit jqueryAdmin 2.x zu erm�glichen
  + kleine sch�nheitsfehler in den Coretemplates beseitigt

  v1.03 & 1.04 (Pumpi, August 2010)
  + kleinere Sch�nheitsfehler in der CSS Datei behoben und �berfl�ssig gewordene eintr�ge gel�scht
  + Optimirung der Ordneransicht f�r standartm��ige Thumbs von 150px
  + Lightbox template Content Flow hinzugef�gt
  + kleiner fehler in verschiedenen Templates behoben
  
  V1.05 (Pumpi, 13.08.2010)
  + Einbetten der Highslide Gallery nach Rücksprache mit Urheber des Highslide Scripts
  + kleinere Anpassungen der Templates für einheitlichen Style
  
  V1.10 (Pumpi, September 2010)
  + Änderung der Ordnungsmethode zur WB Core Funktion class.order.php
  + Einfügen der DragTable Funktion von jQuery für Sortierung mit Drag & Drop
  + Aufräumen des Scriptordners für kleinere zipgröße
  + Css Datei bearbeiten button hinzugefügt
  + Thumbnails können nun individuell mittels Javascript (jCrop) nachbearbeitet werden
  + Beheben aller notice Meldungen
  + Einfügen der Thumbnail Verhälniss funktion
  + Im Admin kann nun ausgewählt werden ob die Thumbs nach änderung von größe oder verhältniss neu erzeugt werden sollen
  + beheben kleiner Bugs
  + an die Bilder wurde nun ?t=timestamp angefügt um neu generierte Thumbs immer richtig anzeigen zu lassen
  + das thumbverzeichnis wurde auf fg-thumbs geändert um eventuelle probleme mit anderen Modulen vorzubeugen
  + die thumbs haben nun immer den selben Namen wie das original
  + es ist nun auch möglich die Foldergallery unterhalb des Mediaordners zu benutzen allerdings nicht in den Corefoldern
  + Die größe des Categorierahmen wird nun am ende der view.php der thumbsize angepasst
  
  V1.14 (Pumpi)
  + Behbet einen Thumbbug aus dem Release von 1.10
  
  
  V1.18 (Juerg Rast)
  + Bilder können nun nach Dateiname aufsteigend/absteigend sortiert werden
  + Bilder sortieren überarbeitet (template erstellt)


  V1.20 (Juerg Rast)
  + Bugs behoben beim Aufrufen von nichtvorhandenen Seiten/Kategorien
  + Falls es mehrere Seiten für eine Kategorie gibt werden in der Lightbox jetzt alle Bilder angezeigt.
  + Dateien aufgeräumt

  V1.21 (Juerg Rast)
  + fiexed a lot of bugs
  + repaired libraryAdmin integration

 -------------------------------------------------------------------------------
**/


$module_directory 	= 'foldergallery';
$module_name 		= 'Foldergallery';
$module_function 	= 'page';
$module_version 	= '1.21';
$module_platform 	= '2.80';	

$module_author 		= 'J&uuml;rg Rast; schliffer; Bianka Martinovic; Chio; Pumpi';
$module_license 	= 'GNU General Public License';
$module_description = 'Bildergalerie anhand der Ordnerstruktur erstellen';
$module_home		= 'http://foldergallery.ch';
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
