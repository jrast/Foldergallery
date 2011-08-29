#Foldergallery
## Description
Foldergallery is a module for Websitebaker CMS. It's a imagegallery to handle many images
in different categorie. Categories are based on the folderstructure on the server, so they are created
automaticly if you sync the filesystem with the databese in the foldergallery backend.

##Licence
This module is free software. You can redistribute it and/or modify it under
the terms of the GNU General Public License - version 2 or later, as published
by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

This module is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

##Changelog

###V1.32 (beta version) (Jürg Rast, 30.8.2011)
+ Anpassungen für WB 2.8.2
+ verschiedene kleine Schönheitsfehler korrigiert

###V1.31 (beta version) (Jürg Rast, 16.05.2011)
+ BugFix: Backend-Upload, allow Files with special chars
+ BugFix: Create new categories: filter special chars in folder name
+ BugFix: Wrong thumbpath in view.php

###V1.30 (beta version) (Jürg Rast, 15.05.2011)
+ BugFix: Allow to display more than one foldergallery-section per page (with some restrictions)
+ BugFix: Some new language-variables added
+ BugFix: Removed restriction for the length of categoriedescription
+ Feautre: New Layout of the Settingstable (allow to create new settings "on the fly")
+ Feautre: Backend-Upload with jQuery and Flash
+ Feautre: Enable/Disable categorie with a click
+ Feautre: Create new categories via backend
+ Moved files to admin-folder to get a better structure
+ Added a simple Help/Infopage
+ Some work on the design
+ cleand up code
+ removed unused lightbox-scripts
+ updated lightbox-scripts
+ removed old files


###V1.21 (Jürg Rast, 30.03.2011)
+ BugFix: Some categories where not displayed 
+ BugFix: Library-/jQueryAdmin integration was missing
+ Made infomessages better visible (eg.: during saving the settings, sync, new page)

This Changelog is from now on in english, a german changelog  can be found on foldergallery.ch

###V1.20 (Juerg Rast, 09.03.2011)
+ Bugs behoben beim Aufrufen von nichtvorhandenen Seiten/Kategorien
+ Falls es mehrere Seiten für eine Kategorie gibt werden in der Lightbox jetzt alle Bilder angezeigt.
+ Dateien aufgeräumt

###V1.18 (Juerg Rast)
+ Bilder können nun nach Dateiname aufsteigend/absteigend sortiert werden
+ Bilder sortieren überarbeitet (template erstellt)

###V1.14 (Pumpi)
+ Behbet einen Thumbbug aus dem Release von 1.10

###V1.10 (Pumpi, September 2010)
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

###V1.05 (Pumpi, 13.08.2010)
+ Einbetten der Highslide Gallery nach Rücksprache mit Urheber des Highslide Scripts
+ kleinere Anpassungen der Templates für einheitlichen Style

###v1.03 & 1.04 (Pumpi, August 2010)
+ kleinere Schönheitsfehler in der CSS Datei behoben und überflüssig gewordene einträge gelöscht
+ Optimirung der Ordneransicht für standartmässige Thumbs von 150px
+ Lightbox template Content Flow hinzugefügt
+ kleiner fehler in verschiedenen Templates behoben

###v1.02 (Pumpi und Webbird, Juni 2010)
+ Erweiterung der Templates um zusammenarbeit mit jqueryAdmin 2.x zu ermöglichen
+ kleine schönheitsfehler in den Coretemplates beseitigt

###v1.01 (Pumpi, Juli 2010)
+ Templateverarbeitung umgestellt um leichter weitere Lightboxes einzufügen
+ Anpassung der Thumberstellung um ein Ordentliches Erscheinungsbild im Frontent zu bieten.
+ ändern der Thumb und Ornerübersicht

###v0.9c (Chio, Juni 2010)
+ Kreuz und quer durch alle Scripts.

###v0.9 (Bianka Martinovic; 28.01.2010)
+ Lightbox jetzt im Backend auswählbar
+ Breadcrumb in Unterkategorien
+ Korrektur: Paging (es kamen nicht die Bilder, die man erwartet hat)
+ CSS aufbereitet

###v0.8 (Bianka Martinovic; 28.10.2009)
+ englisches Sprachmodul
+ Korrektur: Einstellung für "unsichtbare" Verzeichnisse wurde beim Syncen nicht berücksichtigt
+ Korrektur: Klasse für NyroModal (nyroModal statt nyromodal)

###v0.7 (Bianka Martinovic; 27.10.2009)
+ Unterstützung "NyroModal"

###v0.6 (Bianka Martinovic; 27.10.2009)
+ Unterstützung "Highslide"

###v0.5 (Bianka Martinovic; 27.10.2009)
+ Anzahl der Bilder pro Seite im Backend einstellbar (Default: 15)

###v0.4 (Bianka Martinovic; 26.10.2009)
+ Korrektur: Das Rootverzeichnis wird nun auch als Kategorie verwaltet
+ Kategorietitel = Seitenüberschrift, Default: "Bildergalerie" (aus Sprachfile)
+ ermöglicht die Verwaltung der Bilder im Rootverzeichnis

###v0.3  (schliffer; 17.10.2009)
+ Sortierung der Kategorien angepasst
+ Löschen von Ordner auf Laufwerk verhindern
+ Thumbnails ohne weissen Hintergrund erstellen

###v0.2 (Jürg Rast; 6.5.2009)
+ Datenbankstruktur angepasst
+ Kategorie beschreibung hinzugefügt
+ Sortierung von Kategorien möglich
+ Backend erneuert
+ Filesystem <-> DB Synchronisierung verbessert

###v0.13 (Jürg Rast; 18.2.2009)
+ Designfehler behoben

###v0.12 (Jürg Rast; 16.2.2009)
+ in der view.php rontend.css überprüfung hinzugefügt.
+ im backend funktion "alle Ordner neu scannen" entfernt.

###v0.12 (Jürg Rast; 14.2.2009)
+ Frontend verbessert (Fehlermeldung bei nicht vorhandnen Kategorien)
+ Backend:
    + Neu scannen wird jetzt automatisch erledigt (in "allgemeine Einstellungen")
    + add.php: Initialwerte hinzugefügt und direkte weiterleitung auf Einstellungen
+ shutter in scripts Ordner verschoben
+ Allen Ordner eine index.php verpasst

###v0.11 (Jürg Rast; 12.2.2009)
+ Fehler im Frontend behoben
+ in allen Ordner eine index.php hinzugefügt

###v0.1  (Jürg Rast; 12.2.2009)
+ erstes Release des Moduls