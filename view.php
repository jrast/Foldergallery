<?php

// Direkter Zugriff verhindern
if (!defined('WB_PATH')) die (header('Location: index.php'));

// include the default language
require_once(WB_PATH .'/modules/foldergallery/languages/EN.php');
// check if module language file exists for the language set by the user (e.g. DE, EN)
if(file_exists(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php')) {
    require_once(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php');
}

// check if frontend.css file needs to be included into the <body></body> of view.php
if ((!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) &&
        file_exists(WB_PATH . '/modules/foldergallery/frontend.css')) {
    echo '<style type="text/css">';
    include(WB_PATH . '/modules/foldergallery/frontend.css');
    echo "\n</style>\n";
}

// check if frontend.js file needs to be included into the <body></body> of view.php
if ((!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_JAVASCRIPT_REGISTERED')) &&
        file_exists(WB_PATH . '/modules/foldergallery/frontend.js')) {
    echo '<script type="text/javascript" src="' . WB_URL . '/modules/foldergallery/frontend.js"></script>';
}


//  Set the mySQL encoding to utf8
$oldMysqlEncoding = mysql_client_encoding();
mysql_set_charset('utf8',$database->db_handle);

$generatethumbscounter = 0;
// Files includen
require_once (WB_PATH . '/modules/foldergallery/info.php');
require_once (WB_PATH . '/modules/foldergallery/scripts/functions.php');
require_once (WB_PATH . '/modules/foldergallery/class/class.upload.php');
require_once (WB_PATH . '/modules/foldergallery/class/validator.php');
require_once (WB_PATH.'/modules/foldergallery/class/DirectoryHandler.Class.php');

$validator = new Validator();

// Foldergallery Einstellungen
$settings = getSettings($section_id);
$root_dir = $settings['root_dir']; //Chio
$catpic = (int) $settings['catpic']; //Chio

// Einstellungen 
// Link zur Seite
$query_pages = $database->query("SELECT link FROM " . TABLE_PREFIX . "pages WHERE page_id = '$page_id' LIMIT 1");
$page = $query_pages->fetchRow();
$link = WB_URL . PAGES_DIRECTORY . $page['link'] . PAGE_EXTENSION;
$path = WB_PATH;

$ergebnisse = array(); // Da drin werden dann alle Ergebnisse aus der DB gespeichert
$unterKats = array(); // Hier rein kommen die Unterkategorien der aktuellen Kategorie
$bilder = array(); // hier kommen alle Bilder der aktuellen Kategorie rein
$title = PAGE_TITLE; // Page title of the actual page (WB Core)

// Ist die angegebene Kategorie gültig? (erlaubter String)
if (isset($_GET['cat'])) {
    $aktuelleKat = urldecode($_GET['cat']);
    //$aktuelleKat = FG_cleanCat(urldecode($_GET['cat']));
} else {
    $aktuelleKat = '';
    $FG_Error['CatNotValid'] = true;
}

// Die Kategorie ID wird erst für die Bilder gebraucht!
// Jedoch lässt sich so einfach feststellen ob eine Kategorie vorhanden ist
try {
    $aktuelleKat_id = FG_getCatId($section_id, $aktuelleKat);
} catch (Exception $e) {
    $aktuelleKat = '';
    $aktuelleKat_id = FG_getCatId($section_id, $aktuelleKat);
    $FG_Error['CatNotValid'] = true;
}

//SQL für die Kategorien
$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'mod_foldergallery_categories WHERE '
        . 'section_id=' . $section_id . ' AND parent="' . $aktuelleKat . '" AND is_empty=0 AND active=1'
        . ' ORDER BY position DESC';

// OK, Angaben aus DB holen
$query = $database->query($sql);
while ($ergebnis = $query->fetchRow(MYSQL_ASSOC)) {
    $ergebnisse[] = $ergebnis;
}


if (count($ergebnisse) == 0) {
    $error['NoImages'] = true;
} else {
    // Vorschaubild auswählen:
    switch ($catpic) {
        case 0:
            $catpicstring = 'RAND()';
            break;
        case 1:
            $catpicstring = 'position ASC';
            break;
        case 2:
            $catpicstring = 'position DESC';
            break;
        default :
            $catpicstring = 'RAND()';
    }

    foreach($ergebnisse as $ergebnis) {
        $catCrumb  = $ergebnis['parent'] . '/' .$ergebnis['categorie'];
        $catLink   = $link.'?cat='.$catCrumb;
        $catName   = $ergebnis['cat_name'];
        $catID     = $ergebnis['id'];
        $catChilds = $ergebnis['childs'];

        // OK, lets find a preview Image for this categorie
        $sql = 'SELECT file_name, id, parent_id FROM '.TABLE_PREFIX.'mod_foldergallery_files '
             . 'WHERE parent_id ='.$catID.' '
             . 'ORDER BY '.$catpicstring
             . ' LIMIT 1;';
        $query = $database->query($sql);
        if($query->numRows() == 0) {
            // OK, Categorie itself contains no images
            $sql = 'SELECT file_name, id, parent_id FROM '.TABLE_PREFIX.'mod_foldergallery_files '
                 . 'WHERE parent_id IN ('.$catID.$catChilds.') '
                 . 'ORDER BY parent_id ASC, '.$catpicstring
                 . ' LIMIT 1;';
            $query = $database->query($sql);
        }
        $imageData      = $query->fetchRow(MYSQL_ASSOC);
        $imageID        = $imageData['id'];
        $imageName      = $imageData['file_name'];
        $imageParentID  = $imageData['parent_id'];

        if($imageParentID != $catID) {
            // OK, its a image of a subcat, so we need the folder of this cat
            $sql = 'SELECT id, parent, categorie FROM '.TABLE_PREFIX.'mod_foldergallery_categories '
                 . 'WHERE id='.$imageParentID.';';
            $query = $database->query($sql);
            $result = $query->fetchRow(MYSQL_ASSOC);
            $imageCrumb = $root_dir.$result['parent'].'/'.$result['categorie'];
        } else {
            $imageCrumb = $root_dir.$catCrumb;
        }

        // Create the thumb for a categorie
        $imagePath = WB_PATH.$imageCrumb.'/'.$imageName;
        $thumbPath = WB_PATH.$imageCrumb.$thumbdir;
        $thumbImagePath = WB_PATH.$imageCrumb.$thumbdir.'/'.$imageName;
        $thumbImageURL = WB_URL.$imageCrumb.$thumbdir.'/'.$imageName;
        if(!is_file(DirectoryHandler::DecodePath($thumbImagePath))) {
            FG_createThumb($imagePath, $imageName, $thumbPath, $settings['tbSettings']);
        }

        // Create a array for the template
        $unterKats[] = array(
            'link'  => $catLink,
            'thumb' => $thumbImageURL,
            'name'  => $catName
        );
    }
}


// Gibt es Bilder in dieser Kategorie
$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'mod_foldergallery_files WHERE parent_id="' . $aktuelleKat_id . '" ORDER BY position ASC;';
$query = $database->query($sql);
while ($bild = $query->fetchRow()) {
    if ($bild['file_name'] == 'folderpreview.jpg')
        continue;
    $bilder[] = $bild;
}

//echo '<h1>'.$aktuelleKat_id.'</h1>';
if (count($bilder) != 0) {

    $error = false;
    $temp = explode('/', $aktuelleKat);
    $bildkat = array_pop($temp);
    $bildparent = implode('/', $temp);
    $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'mod_foldergallery_categories  WHERE section_id=' . $section_id . ' AND categorie="' . $bildkat . '" AND parent="' . $bildparent . '" LIMIT 1;';
    $query = $database->query($sql);
    $result = $query->fetchRow();
    $titel = $result['cat_name'];
    $description = $result['description'];

    if (!empty($result['categorie']))
        $folder = $root_dir . $result['parent'] . '/' . $result['categorie'] . '/';
    else
    $folder = $root_dir . $result['parent'] . '/';
    $pathToFolder = WB_PATH . $folder;
    $pathToThumb = WB_PATH . $folder . $thumbdir1;
    $urlToFolder = $url . $folder;
    $urlToThumb = $url . $folder . $thumbdir1 . '/';
}

// Zur�ck Link
if ($aktuelleKat) {
    $temp = explode('/', $aktuelleKat);
    array_pop($temp);
    $parent = implode('/', $temp);
    $back_link = $link . '?cat=' . $parent;
    $hidden = '';
} else {
    $hidden = 'style="display:none"';
    $back_link = '#';
}



// Template
if (file_exists(dirname(__FILE__) . '/templates/view_' . $settings['lightbox'] . '.htt')) {
    $viewTemplate = 'view_' . $settings['lightbox'] . '.htt';
    $t = new Template(dirname(__FILE__) . '/templates', 'remove');
}
elseif( file_exists( WB_PATH.'/modules/'.$settings['lightbox'].'/foldergallery_template.htt' ) )
{
  $viewTemplate = 'foldergallery_template.htt';
	$t = new Template(WB_PATH.'/modules/'.$settings['lightbox'], 'remove');
	$parts = explode( '/', $settings['lightbox'] );
	echo "[[LibInclude?lib=".$parts[0]."&amp;plugin=".$parts[2]."]]";
}
elseif( file_exists( WB_PATH.'/modules/jqueryadmin/plugins/'.$settings['lightbox'].'/foldergallery_template.htt' ) )
{
  $viewTemplate = 'foldergallery_template.htt';
	$t = new Template(WB_PATH.'/modules/jqueryadmin/plugins/'.$settings['lightbox'], 'remove');
	echo "[[jQueryInclude?plugin=".$settings['lightbox']."]]";
}
else {
    $viewTemplate = 'view.htt';
// --- added by WebBird, 29.07.2010 ---
    $t = new Template(dirname(__FILE__) . '/templates', 'remove');
// --- end added by WebBird, 29.07.2010 ---
}

// --- commented by WebBird, 29.07.2010 ---
$t->halt_on_error = 'no';
$t->set_file('view', $viewTemplate);
$t->set_block('view', 'CommentDoc');
$t->clear_var('CommentDoc');
$t->set_block('view', 'categories', 'CATEGORIES');
$t->set_block('categories', 'show_categories', 'SHOW_CATEGORIES');
$t->set_block('view', 'images', 'IMAGES');
$t->set_block('images', 'thumbnails', 'THUMBNAILS');
$t->set_block('images', 'invisiblePre', 'INVISIBLEPRE'); // Für weitere Bilder
$t->set_block('images', 'invisiblePost', 'INVISIBLEPOST');
$t->set_block('view', 'hr', 'HR');
$t->set_block('view', 'error', 'ERROR');  // Dieser Fehler wird nicht ausgegeben, BUG
$t->set_block('view', 'pagenav', 'PAGE_NAV');

// As the error reporting is not implemented in the frontend, set error to false
$error = false;
if ($error) {
    $t->set_var('FRONT_END_ERROR_STRING', $MOD_FOLDERGALLERY['FRONT_END_ERROR']);
    $t->parse('ERROR', 'error');
} else {
    $t->clear_var('error');
}

$t->set_var(array(
    'VIEW_TITLE' => $title,
    'BACK_LINK' => $back_link,
    'BACK_STRING' => $MOD_FOLDERGALLERY['BACK_STRING'],
    'HIDDEN' => $hidden,
));


// Kategorien anzeigen
if ($unterKats) {
    //$t->set_var('CATEGORIES_TITLE', $MOD_FOLDERGALLERY['CATEGORIES_TITLE']);
    foreach ($unterKats as $kat) {
        $t->set_var(array(
            'CAT_LINK' => $kat['link'],
            'THUMB_LINK' => $kat['thumb'] . '?t=' . time(),
            'CAT_CAPTION' => $kat['name']
        ));
        $t->parse('SHOW_CATEGORIES', 'show_categories', true);
    }
    $t->parse('CATEGORIES', 'categories', true);
} else {
    // Falls keine Kategorien vorhanden sind kann der Block gel�scht werden
    $t->clear_var('show_categories');
    $t->clear_var('categories');
}
// Fertig Kategorien angezeigt
// Bilder anzeigen
if ($bilder) {
    $anzahlBilder = count($bilder);
    if ($anzahlBilder > $settings['pics_pp']) {
        $pages = ceil($anzahlBilder / $settings['pics_pp']);
    }

    if (isset($_GET['p']) && is_numeric($_GET['p'])) {
        if ($_GET['p'] <= $pages) {
            $current_page = $_GET['p'];
        } else {
            $current_page = 1;
        }
    } else {
        $current_page = 1;
    }

    $t->set_var('CAT_TITLE', $titel);
    $t->set_var('CAT_DESCRIPTION', $description);

    if (is_numeric($pages)) {
        $pages_navi = '<ul class="fg_pages_nav">';
        for ($i = 1; $i <= $pages; $i++) {
            //erzeugt nur ein ?cat wenn auch $aktuelleKat nicht leer ist verhindert notice Warunung
            if (empty($aktuelleKat))
                $pages_navi .= "<li><a href=\"$link?p=$i\"";
            else
                $pages_navi .= "<li><a href=\"$link?cat=$aktuelleKat&p=$i\"";

            if (isset($_GET['p']) && $_GET['p'] == $i) {
                $pages_navi .= ' class="current"';
            }
            $pages_navi .= ">$i</a></li>";
        }
        $pages_navi .= '</ul>';
        $t->set_var(
                array(
                    'PAGE_NAV' => $pages_navi,
                    'PAGE' => $MOD_FOLDERGALLERY['PAGE']
                )
        );
        $t->parse('PAGE_NAV', 'pagenav');
    }
    else {
        $t->clear_var('pagenav');
    }


    $offset = ( $settings['pics_pp'] * $current_page - $settings['pics_pp'] );
    for($i = 0; $i < $anzahlBilder; $i++) {
        $bildfilename = $bilder[$i]['file_name'];
        $thumb = $pathToThumb. '/' . $bildfilename;
        $tumburl = $urlToThumb . $bildfilename;
        $file = $pathToFolder . $bildfilename;
        if (!is_file(DirectoryHandler::DecodePath($file))) {
            //echo '<h1>|'.$bildfilename.'|</h1>';
            $deletesql = 'DELETE FROM ' . TABLE_PREFIX . 'mod_foldergallery_files WHERE id=' . $bilder[$i]['id'];
            $database->query($deletesql);
            continue;
        }
        if (!is_file(DirectoryHandler::DecodePath($thumb))) {
            $file = $pathToFolder . $bildfilename;
            FG_createThumb($file, $bildfilename, $pathToThumb, $settings['tbSettings']);
        }

        if ($settings['lightbox'] != 'contentFlow')
            $timeadd = '?t=' . time();
        else
            $timeadd = '';

        $t->set_var(array(
            'ORIGINAL' => $urlToFolder . $bildfilename,
            'THUMB' => $tumburl . '?t=' . time(),
            'CAPTION' => $bilder[$i]['caption'],
            'NUMBER' => $i
        ));

        // Bild sichtbar oder unsichtbar?
        if( $i < $offset) {
            $t->parse('INVISIBLEPRE', 'invisiblePre', true);
        } elseif ($i > ($offset + $settings['pics_pp'] - 1)) {
            $t->parse('INVISIBLEPOST', 'invisiblePost', true);
        } else {
            $t->parse('THUMBNAILS', 'thumbnails', true);
        }
    }
    $t->parse('IMAGES', 'images', true);

} else {
    $t->clear_var('thumbnails');
    $t->clear_var('images');
}


// Kategorien
if ($bilder && $unterKats) {
    $t->parse('HR', 'hr', true);
} else {
    $t->clear_var('hr');
}


if ($aktuelleKat != '') {
    $path = explode('/', $aktuelleKat);
    $bread = '<ul class="fg_pages_nav">'
            . '<li><a href="'
            . $link
            . '">'
            . $MOD_FOLDERGALLERY['BACK_STRING']
            . '</a></li>';

    // first element is empty as the string begins with /
    array_shift($path);
    foreach ($path as $i => $cat_name) {
        $catres = $database->query("SELECT cat_name FROM " . TABLE_PREFIX . "mod_foldergallery_categories WHERE categorie = '$cat_name' LIMIT 1");
        $cat = $catres->fetchRow();
        $bread .= '<li> <a href="'
                . $link
                . '?cat=/' . implode('/', array_slice($path, 0, ($i + 1)))
                . '">' . $cat['cat_name'] . '</a></li>';
    }

    $bread .= '</ul><br /><br />';
    $t->set_var('CATBREAD', $bread);
    $t->set_var('CATEGORIES_TITLE', $cat['cat_name']);
}

$t->set_var('WB_URL', WB_URL);

//überschreibt die fest eingestellte Größe von ul.categories li a auf die Thumbgrößenwerte
$catWidth = $settings['tbSettings']['image_x'] + 10;
$catHeight = $settings['tbSettings']['image_y'] + 10;

echo '<style type="text/css">
ul.categories li a {
	width: ' . $catWidth . 'px;
	height: ' . $catHeight . 'px;
}
</style>';

$t->pparse('output', 'view');

// reset the mySQL encoding
mysql_set_charset($oldMysqlEncoding, $database->db_handle);
?>
