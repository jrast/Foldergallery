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
$module_description = 'Maak een foto gallery met fotoalbums (categorie&euml;n) gebaseerd op mappen.';

//Variables for the Frontend
$MOD_FOLDERGALLERY['VIEW_TITLE']        = 'Fotogalerij';
$MOD_FOLDERGALLERY['CATEGORIES_TITLE']  = 'categorie&euml;n';
$MOD_FOLDERGALLERY['BACK_STRING']       = 'Terug naar overzicht';
$MOD_FOLDERGALLERY['FRONT_END_ERROR']   = 'Deze categorie bestaat niet of bevat geen foto\'s en/of categorie&euml;n!';
$MOD_FOLDERGALLERY['PAGE']              = 'Pagina';


//Variables for the Backend
$MOD_FOLDERGALLERY['PICS_PP']           = 'Foto\'s per pagina';
$MOD_FOLDERGALLERY['LIGHTBOX']          = 'Lightbox';
$MOD_FOLDERGALLERY['MODIFY_CAT_TITLE']  = 'Bewerk categorie&euml;n en foto\'s';
$MOD_FOLDERGALLERY['MODIFY_CAT']        = 'Bewerk categorie details:';
$MOD_FOLDERGALLERY['CAT_NAME']          = 'Titel categorie:';
$MOD_FOLDERGALLERY['CAT_DESCRIPTION']   = 'Categorie beschrijving:';
$MOD_FOLDERGALLERY['MODIFY_IMG']        = 'Foto\'s bewerken:';
$MOD_FOLDERGALLERY['IMAGE']             = 'Foto';
$MOD_FOLDERGALLERY['IMAGE_NAME']        = 'Naam foto';
$MOD_FOLDERGALLERY['IMG_CAPTION']       = 'Foto beschrijving';
$MOD_FOLDERGALLERY['REDIRECT']		= 'Voordat je gebruik kunt maken van de fotogalerij moet je eerst het nodige instellen. '
                                        . 'Je wordt binnen een paar seconden doorgelinkt (als javascript is geactiveerd)';
$MOD_FOLDERGALLERY['TITEL_BACKEND']     = 'Fotoalbum bewerken';
$MOD_FOLDERGALLERY['TITEL_MODIFY']      = 'Bewerk categorie&euml;n en foto\'s:';
$MOD_FOLDERGALLERY['SETTINGS']          = 'Algemene instellingen';
$MOD_FOLDERGALLERY['ROOT_DIR']          = 'Hoofdmap';
$MOD_FOLDERGALLERY['EXTENSIONS']        = 'Toegestane extensies';
$MOD_FOLDERGALLERY['INVISIBLE']         = 'Mappen verbergen';
$MOD_FOLDERGALLERY['NEW_SCANN_INFO']    = 'Deze bewerking is opgeslagen in de database. De miniaturen zullen gemaakt worden wanneer de categorie voor het eerst bekeken wordt.';
$MOD_FOLDERGALLERY['FOLDER_NAME']       = 'Naam map';
$MOD_FOLDERGALLERY['DELETE']            = 'Verwijderen?';
$MOD_FOLDERGALLERY['ERROR_MESSAGE']     = 'Geen data!';
$MOD_FOLDERGALLERY['DB_ERROR']          = 'Database error!';
$MOD_FOLDERGALLERY['FS_ERROR']          = 'Map verwijderen mislukt!';
$MOD_FOLDERGALLERY['NO_FILES_IN_CAT']   = 'Deze categorie bevat geen foto\'s!';
$MOD_FOLDERGALLERY['SYNC']              = 'Synchroniseer database met de huidige veranderingen.';
$MOD_FOLDERGALLERY['EDIT_CSS']          = 'Bewerk CSS';
$MOD_FOLDERGALLERY['FOLDER_IN_FS']      = 'Huidige map:';
$MOD_FOLDERGALLERY['CAT_TITLE']         = 'Titel categorie:';
$MOD_FOLDERGALLERY['ACTIONS']           = 'Acties:';
$MOD_FOLDERGALLERY['NO_CATEGORIES']     = 'Geen categorie (=submappen) gevonden<br /><br />De fotogalerij zal werken, maar er zullen geen categorie&euml;n getoont kunnen worden.';
$MOD_FOLDERGALLERY['EDIT_THUMB']        = 'Bewerk thumbnail';
$MOD_FOLDERGALLERY['EDIT_THUMB_DESCRIPTION']    = '<strong>Selecteer nieuwe afbeelding aub</strong>';
$MOD_FOLDERGALLERY['EDIT_THUMB_BUTTON']         = 'Maak nieuwe thumbnail';
$MOD_FOLDERGALLERY['THUMB_SIZE']                = 'Thumbnail grootte';
$MOD_FOLDERGALLERY['THUMB_RATIO']               = 'Thumbnail verhouding';
$MOD_FOLDERGALLERY['THUMB_NOT_NEW']             = 'Geen thumbnails genereren';
$MOD_FOLDERGALLERY['CHANGING_INFO']		= 'Grootte of Verhouding aanpassen van de thumbnails, verwijderd de huidige thumnails en wordt opnieuw gegenereerd.';
$MOD_FOLDERGALLERY['SYNC_DATABASE']             = 'Synchroniseer bestanden met database...';
$MOD_FOLDERGALLERY['SAVE_SETTINGS']             = 'Veranderingen zijn opgeslagen...';
$MOD_FOLDERGALLERY['BACK']                      = 'Back';
$MOD_FOLDERGALLERY['REORDER_INFO_STRING']       = 'Reorder result will be displayed here.';
$MOD_FOLDERGALLERY['REORDER_INFO_SUCESS']       = 'Saved new order sucessfully!';
$MOD_FOLDERGALLERY['REORDER_IMAGES']            = 'Sort Images';
$MOD_FOLDERGALLERY['SORT_BY_NAME']              = 'Sort images by filename';
$MOD_FOLDERGALLERY['SORT_BY_NAME_ASC']          = 'filename ascending';
$MOD_FOLDERGALLERY['SORT_BY_NAME_DESC']         = 'filename descending';
$MOD_FOLDERGALLERY['SORT_FREEHAND']             = 'Free sort (Drag & Drop)';

// Tooltips
$MOD_FOLDERGALLERY['ROOT_FOLDER_STRING_TT'] = 'Dit is de hoofdmap (root) waar gezocht wordt naar foto\'s en mappen. '
                                            . 'Verander deze map niet meer! Alle instellingen en gegevens van de foto\'s zullen verloren gaan!';
$MOD_FOLDERGALLERY['EXTENSIONS_STRING_TT']  = 'Definieer de extensies die je beschikbaar wilt maken. (Hoofdlettergevoelig.) Gebruik "," (komma) als scheidingsteken.';
$MOD_FOLDERGALLERY['INVISIBLE_STRING_TT']   = 'Mappen die hier aangegeven worden zullen niet zichtbaar zijn in de gallery.';
$MOD_FOLDERGALLERY['DELETE_TITLE_TT']       = 'Waarschuwing: Dit verwijderd alle categorie&euml;n en foto\'s! (De foto\'s zullen dus ook verwijderd worden!)';
?>
