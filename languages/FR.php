<?php

/*

  Website Baker Project http://www.websitebaker.org/
  Copyright (C) 2008-2011, Jürg Rast

  Website Baker is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  Website Baker is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Website Baker; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

 */

//Modul Description
$module_description = 'Create an Image Gallery with folders as categories';

//Variables for the Frontend
$MOD_FOLDERGALLERY['VIEW_TITLE'] = 'Gallerie Photos';
$MOD_FOLDERGALLERY['CATEGORIES_TITLE'] = 'Catégories';
$MOD_FOLDERGALLERY['BACK_STRING'] = 'Retour';
$MOD_FOLDERGALLERY['FRONT_END_ERROR'] = 'Cette cat&egorie est vide!';
$MOD_FOLDERGALLERY['PAGE'] = 'Page';

//Variables for the Backend
$MOD_FOLDERGALLERY['PICS_PP'] = 'Images par page';
$MOD_FOLDERGALLERY['LIGHTBOX'] = 'Lightbox';
$MOD_FOLDERGALLERY['MODIFY_CAT_TITLE'] = 'Modifier les catégories et détails';
$MOD_FOLDERGALLERY['MODIFY_CAT'] = 'Modifier les détails de la catégorie:';
$MOD_FOLDERGALLERY['CAT_NAME'] = 'Nom et titre de la catégorie:';
$MOD_FOLDERGALLERY['CAT_DESCRIPTION'] = 'Description de la catégorie:';
$MOD_FOLDERGALLERY['MODIFY_IMG'] = 'Modifier les images:';
$MOD_FOLDERGALLERY['IMAGE'] = 'Image';
$MOD_FOLDERGALLERY['IMAGE_NAME'] = 'Nom de l\'image';
$MOD_FOLDERGALLERY['IMG_CAPTION'] = 'Description de l\'image';
$MOD_FOLDERGALLERY['REDIRECT'] = 'Vous devez changer la configuration avant de continuer. '
        . 'Vous allez etre rediriger dans 2 secondes. (si JavaScript est activée.)';
$MOD_FOLDERGALLERY['TITEL_BACKEND'] = 'Administration de Foldergallery';
$MOD_FOLDERGALLERY['TITEL_MODIFY'] = 'Modifier les catégories et les images:';
$MOD_FOLDERGALLERY['SETTINGS'] = 'Configuration commune';
$MOD_FOLDERGALLERY['ROOT_DIR'] = 'Racine';
$MOD_FOLDERGALLERY['EXTENSIONS'] = 'Extensions permises';
$MOD_FOLDERGALLERY['INVISIBLE'] = 'Répertoires cachés';
$MOD_FOLDERGALLERY['NEW_SCANN_INFO'] = 'Cette action a crée des entré dans la base de données. Les vignettes sont créés quand les cat&ecute;gories seront affichées pour le première fois.';
$MOD_FOLDERGALLERY['FOLDER_NAME'] = 'Non du répertoire';
$MOD_FOLDERGALLERY['DELETE'] = 'Supprimer?';
$MOD_FOLDERGALLERY['ERROR_MESSAGE'] = 'Sans données!';
$MOD_FOLDERGALLERY['DB_ERROR'] = 'Erreur de BD!';
$MOD_FOLDERGALLERY['FS_ERROR'] = 'Incapable de supprimer le répertoire!';
$MOD_FOLDERGALLERY['NO_FILES_IN_CAT'] = 'Cette catégorie ne contient aucune images !';
$MOD_FOLDERGALLERY['SYNC'] = 'Synchroniser la DB';
$MOD_FOLDERGALLERY['EDIT_CSS'] = 'Éditer le CSS';
$MOD_FOLDERGALLERY['FOLDER_IN_FS'] = 'Répertoire:';
$MOD_FOLDERGALLERY['CAT_TITLE'] = 'Titre de la catégorie:';
$MOD_FOLDERGALLERY['ACTION'] = 'Actions:';
$MOD_FOLDERGALLERY['NO_CATEGORIES'] = 'Catégorie (=sous-répertoire) non rtouv&ecute;s.

Le module fonctionnera quant meme.';
$MOD_FOLDERGALLERY['EDIT_THUMB'] = 'Éditer la vignette';
$MOD_FOLDERGALLERY['EDIT_THUMB_DESCRIPTION'] = 'Veuillez sélectionner une image';
$MOD_FOLDERGALLERY['EDIT_THUMB_BUTTON'] = 'Créé la vignette';
$MOD_FOLDERGALLERY['THUMB_SIZE'] = 'Dimension de la vignette';
$MOD_FOLDERGALLERY['THUMB_RATIO'] = 'Ratio de la vignette';
$MOD_FOLDERGALLERY['THUMB_NOT_NEW'] = 'Ne pas recréé les vignettes';
$MOD_FOLDERGALLERY['CHANGING_INFO'] = 'Les changements dimension ou ratio supprimerons (et recréerons) vos vignettes.';
$MOD_FOLDERGALLERY['SYNC_DATABASE'] = 'Synchronisation avec la BD...';
$MOD_FOLDERGALLERY['SAVE_SETTINGS'] = 'La configuration est sauvegard&eacutee...';
$MOD_FOLDERGALLERY['SORT_IMAGE'] = 'Trier les images';
$MOD_FOLDERGALLERY['BACK'] = 'Retour';
$MOD_FOLDERGALLERY['REORDER_INFO_STRING'] = 'Le résultat du tri sera affiché ici.';
$MOD_FOLDERGALLERY['REORDER_INFO_SUCESS'] = 'Mis á jour di tri est complete!';
$MOD_FOLDERGALLERY['REORDER_IMAGES'] = 'Trier les images';
$MOD_FOLDERGALLERY['SORT_BY_NAME'] = 'Trier les images par leurs noms de fichier';
$MOD_FOLDERGALLERY['SORT_BY_NAME_ASC'] = 'fichier croissant';
$MOD_FOLDERGALLERY['SORT_BY_NAME_DESC'] = 'fichier décroissant';
$MOD_FOLDERGALLERY['SORT_FREEHAND'] = 'Tri selon votre choix (Drag & Drop)';

// Tooltips
$MOD_FOLDERGALLERY['ROOT_FOLDER_STRING_TT'] = 'Ceci est le répertoire de base (racine) qui sera parcouru pour retrouver vos images (récursivement). '
        . 'S.V.P ne pas changer ce répertoire dans le futur, sinon votre configuration sera perdu!';
$MOD_FOLDERGALLERY['EXTENSIONS_STRING_TT'] = 'Définir les suffixes permis ici. (insensible à la case.) Utilisez la vigule (",").';
$MOD_FOLDERGALLERY['INVISIBLE_STRING_TT'] = 'Ces répertoires ne seront pas parcourus.';
$MOD_FOLDERGALLERY['DELETE_TITLE_TT'] = 'Avertissement: Ceci supprimera TOUT vos catégories et fichiers!';
?>
