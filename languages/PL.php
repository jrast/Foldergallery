<?php

/*

Website Baker Project <http://www.websitebaker.org/>
Copyright (C) 2008-2011, Jürg Rast

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

// polish translation 1.0
// traductor: Wojciech Jodła - www.wujitsu.pl

//Modul Description
$module_description = 'Tworzenie galerii obrazów z folderami jako kategorie';

//Variables for the Frontend
$MOD_FOLDERGALLERY['VIEW_TITLE']        = 'Galeria obrazów';
$MOD_FOLDERGALLERY['CATEGORIES_TITLE']  = 'Kategorie';
$MOD_FOLDERGALLERY['BACK_STRING']       = 'Strona główna galerii ';
$MOD_FOLDERGALLERY['FRONT_END_ERROR']   = 'Ta kategoria nie istnieje albo nie zawiera żadnych obrazów bądź podkategorii!';
$MOD_FOLDERGALLERY['PAGE']              = 'Strona';


//Variables for the Backend
$MOD_FOLDERGALLERY['PICS_PP']           = 'Obrazów na stronę';
$MOD_FOLDERGALLERY['LIGHTBOX']          = 'Lightbox';
$MOD_FOLDERGALLERY['MODIFY_CAT_TITLE']  = 'Modyfikacja kategorii i detali obrazów';
$MOD_FOLDERGALLERY['MODIFY_CAT']        = 'Modyfikacja detali kategorii:';
$MOD_FOLDERGALLERY['CAT_NAME']          = 'Nazwa/tytuł kategorii:';
$MOD_FOLDERGALLERY['CAT_DESCRIPTION']   = 'Opis kategorii:';
$MOD_FOLDERGALLERY['MODIFY_IMG']        = 'Modyfikuj obrazy:';
$MOD_FOLDERGALLERY['IMAGE']             = 'Obraz';
$MOD_FOLDERGALLERY['IMAGE_NAME']        = 'Nazwa obrazu';
$MOD_FOLDERGALLERY['IMG_CAPTION']       = 'Opis obrazu';
$MOD_FOLDERGALLERY['REDIRECT']          = 'Musisz skonfigurować galerię zanim będzie możliwe korzystanie z niej. '
                                        . 'Zostaniesz przeniesiony na stronę konfiguracji za 2 sec. (jeśli javascript jest włączony.)';
$MOD_FOLDERGALLERY['TITEL_BACKEND']     = 'Administracja modułem Foldergallery';
$MOD_FOLDERGALLERY['TITEL_MODIFY']      = 'Modyfikuj kategorie i obrazy:';
$MOD_FOLDERGALLERY['SETTINGS']          = 'Podstawowe ustawienia';
$MOD_FOLDERGALLERY['ROOT_DIR']          = 'Katalog główny';
$MOD_FOLDERGALLERY['EXTENSIONS']        = 'Dopuszczalne rozszerzenia plików';
$MOD_FOLDERGALLERY['INVISIBLE']         = 'Ukryj foldery';
$MOD_FOLDERGALLERY['NEW_SCANN_INFO']    = 'Akcja dodała wpisy do bazy danych. Miniatury są tworzone podczas pierwszego wyświetlenia kategorii.';
$MOD_FOLDERGALLERY['FOLDER_NAME']       = 'Nazwa folderu';
$MOD_FOLDERGALLERY['DELETE']            = 'Usunąć ?';
$MOD_FOLDERGALLERY['ERROR_MESSAGE']     = 'Brak danych!';
$MOD_FOLDERGALLERY['DB_ERROR']          = 'Błąd bazy danych!';
$MOD_FOLDERGALLERY['FS_ERROR']          = 'Nie można usunąć folderu!';
$MOD_FOLDERGALLERY['NO_FILES_IN_CAT']   = 'Kategoria nie zawiera żadnych obrazów!';
$MOD_FOLDERGALLERY['SYNC']              = 'Synchronizacja bazy danych z systemem plików';
$MOD_FOLDERGALLERY['EDIT_CSS']          = 'Edytuj CSS';
$MOD_FOLDERGALLERY['FOLDER_IN_FS']      = 'Folder systemowy:';
$MOD_FOLDERGALLERY['CAT_TITLE']         = 'Tytuł kategorii:';
$MOD_FOLDERGALLERY['ACTION']            = 'Akcje:';
$MOD_FOLDERGALLERY['NO_CATEGORIES']     = 'Nie znaleziono żadneych kategorii (=Subfolderów).<br /><br />Galeria będzie działać mimo wszystok, ale nie pokaże żadnych kategorii.';
$MOD_FOLDERGALLERY['EDIT_THUMB']        = 'Edytuj miniaturkę';
$MOD_FOLDERGALLERY['EDIT_THUMB_DESCRIPTION']    = '<strong>Proszę wybrać nowy obraz</strong>';
$MOD_FOLDERGALLERY['EDIT_THUMB_BUTTON']         = 'Kreśl miniaturkę';
$MOD_FOLDERGALLERY['THUMB_SIZE']                = 'Rozmiar miniaturki';
$MOD_FOLDERGALLERY['THUMB_RATIO']               = 'Proporcje miniatur';
$MOD_FOLDERGALLERY['THUMB_NOT_NEW']             = 'Nie przetwarzaj miniatur';
$MOD_FOLDERGALLERY['CHANGING_INFO']             = 'Zmiana <strong>rozmiaru miniatur</strong> lub <strong>proporcji</strong> usunie obecnie istniejące miniatury i utworzy nowe.';
$MOD_FOLDERGALLERY['SYNC_DATABASE']             = 'Synchronizacja bazy danych z systemem plików...';
$MOD_FOLDERGALLERY['SAVE_SETTINGS']             = 'Zapisywanie ustawień...';
$MOD_FOLDERGALLERY['SORT_IMAGE']                = 'Sortowanie obrazów';
$MOD_FOLDERGALLERY['BACK']                      = 'powrót';
$MOD_FOLDERGALLERY['REORDER_INFO_STRING']       = 'Wynik zmiany kolejności będzie wyświetlony tutaj.';
$MOD_FOLDERGALLERY['REORDER_INFO_SUCESS']       = 'Zapis zmiany kolejności zakończony powodzeniem!';
$MOD_FOLDERGALLERY['REORDER_IMAGES']            = 'Sortowanie obrazów';
$MOD_FOLDERGALLERY['SORT_BY_NAME']              = 'Sortowanie po nazwach plików';
$MOD_FOLDERGALLERY['SORT_BY_NAME_ASC']          = 'Nazwy rosnąco';
$MOD_FOLDERGALLERY['SORT_BY_NAME_DESC']         = 'Nazwy malejąco';
$MOD_FOLDERGALLERY['SORT_FREEHAND']             = 'Dowolne sortowanie (przeciągnij i upuść)';
$MOD_FOLDERGALLERY['THUMB_EDIT_ALT']            = 'Edycja miniaturki';
$MOD_FOLDERGALLERY['IMAGE_DELETE_ALT']          = 'Usuń obrazek';
$MOD_FOLDERGALLERY['EDIT_CATEGORIE']            = 'Edytuj kategorie';
$MOD_FOLDERGALLERY['EXPAND_COLAPSE']            = 'Rozwiń/Zwiń';
$MOD_FOLDERGALLERY['MOVE_UP']                   = 'Przesuń w górę';
$MOD_FOLDERGALLERY['MOVE_DOWN']                 = 'Przesuń w dół';
$MOD_FOLDERGALLERY['HELP_INFORMATION']          = 'Pomoc/informacje';
$MOD_FOLDERGALLERY['CAT_ACTIVE']                = 'aktywny, kliknij aby zdeaktywować!';
$MOD_FOLDERGALLERY['CAT_INACTIVE']              = 'nieaktywny, kliknij aby aktywować!';
$MOD_FOLDERGALLERY['CAT_TOGGLE_ACTIV_FAIL']     = 'Aktywacja/deaktywacja tej kategorii niemożliwa! Próbujesz zhakować system ?!';
$MOD_FOLDERGALLERY['DELETE_ARE_YOU_SURE']       = 'Na pewno usunąć ten plik ? Po usunięciu pliku nie można odtworzyć !';
$MOD_FOLDERGALLERY['ADD_MORE_PICS']             = 'Dodaj więcej obrazów do tej kategorii';
$MOD_FOLDERGALLERY['CATPIC_STRINGS'][0]         = 'Losowo';
$MOD_FOLDERGALLERY['CATPIC_STRINGS'][1]         = 'Pierwszy';
$MOD_FOLDERGALLERY['CATPIC_STRINGS'][2]         = 'Ostatni';
$MOD_FOLDERGALLERY['CAT_OVERVIEW_PIC']          = 'Podgląd kategorii';
$MOD_FOLDERGALLERY['THUMBNAIL_SETTINGS']        = 'Ustawienia miniatur';
$MOD_FOLDERGALLERY['LOAD_PRESET']               = 'Ustawienia predefiniowane';
$MOD_FOLDERGALLERY['LOAD_PRESET_INFO']          = '<b>UWAGA! ta funkcja nadpisze wszystkie poniższe pola!</b>';
$MOD_FOLDERGALLERY['IMAGE_CROP']                = 'Przycinanie obrazów';
$MOD_FOLDERGALLERY['IMAGE_DONT_CROP']           = 'bez przycinania';
$MOD_FOLDERGALLERY['IMAGE_DO_CROP']             = 'przycinaj obrazy';
$MOD_FOLDERGALLERY['RATIO']                     = 'Proporcje';
$MOD_FOLDERGALLERY['CALCULATE_RATIO']           = 'Kalkuluj do maksymalnych wartości z poniższych pól';
$MOD_FOLDERGALLERY['MAX_WIDTH']                 = 'Max. szerokość';
$MOD_FOLDERGALLERY['MAX_HEIGHT']                = 'Max. wysokość';
$MOD_FOLDERGALLERY['ADVANCED_SETTINGS']         = 'Zaawansowane ustawienia';
$MOD_FOLDERGALLERY['BACKGROUND_COLOR']          = 'Kolor tła';

$MOD_FOLDERGALLERY['NEW_CAT']                   = 'Stwórz nową kategorię';
$MOD_FOLDERGALLERY['CAT_PARENT']                = 'Kategoria nadrzędna';
$MOD_FOLDERGALLERY['FOLDER_NAME']               = 'Nazwa folderu';
$MOD_FOLDERGALLERY['CAT_TITLE']                 = 'Tytuł kategorii';
$MOD_FOLDERGALLERY['CAT_DESC']                  = 'Opis kategorii';


// Tooltips
$MOD_FOLDERGALLERY['ROOT_FOLDER_STRING_TT']     = 'To jest podstawowy (główny) folder galerii, do którego będą odnosić się zagnieżdżone foldery galerii.' . 'Nie należy zmieniać tego folderu, albowiem wszystkie ustawienia obrazów zostaną utracone!';
$MOD_FOLDERGALLERY['EXTENSIONS_STRING_TT']      = 'Zdefiniuj tutaj sufiksy plików, Define the file suffixes you wish to allow here. (Case insensitive.) Use "," (comma) as delimiter.';
$MOD_FOLDERGALLERY['INVISIBLE_STRING_TT']       = 'Folder that are listed here will not be scanned.';
$MOD_FOLDERGALLERY['DELETE_TITLE_TT']           = 'Warning: This will delete ALL categories and images! (The images will be REMOVED, too!)';
?>
