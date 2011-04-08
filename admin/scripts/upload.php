<?php
// Befor doing anything, make some safety check's:
if(!isset($_REQUEST['secCheck'])) {
    exit;
}

if(empty($_FILES)) {
    exit;
}

// Check the section, page and cat ID:
$secArray = explode('/', $_REQUEST['secCheck']);
if(count($secArray) != 3) {
    exit;
}

if(!is_numeric($secArray[0])) {
    exit;
}
$page_id = $secArray[0];

if(!is_numeric($secArray[1])) {
    exit;
}
$section_id = $secArray[1];

if(!is_numeric($secArray[2])) {
    exit;
}
$cat_id = $secArray[2];

// OK, secCheck Values seems to be ok, at least they are save to use
// Start with WB specific stuff:

require_once('../../../../config.php');
if(defined('WB_PATH') == false) {
    exit();
}

require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Modules', 'module_view', false, false);

require_once (WB_PATH.'/modules/foldergallery/scripts/functions.php');

// check if module language file exists for the language set by the user (e.g. DE, EN)
if (!file_exists(WB_PATH . '/modules/foldergallery/languages/' . LANGUAGE . '.php')) {
    // no module language file exists for the language set by the user, include default module language file EN.php
    require_once(WB_PATH . '/modules/foldergallery/languages/EN.php');
} else {
    // a module language file exists for the language defined by the user, load it
    require_once(WB_PATH . '/modules/foldergallery/languages/' . LANGUAGE . '.php');
}

// Get the settings for this section
$settings = getSettings($section_id);
if($settings['page_id'] != $page_id) {
    exit;
}

// Get some more detailes for this categorie
$sql = 'SELECT id, section_id, parent_id, categorie, parent FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE `id` = '.$cat_id.' AND `section_id` = '.$section_id.';';
$query = $database->query($sql);
if($query->numRows() != 1) {
    exit;
}
$categorie = $query->fetchRow();

$categoriePath = WB_PATH.$settings['root_dir'];

if($categorie['parent_id'] != -1) {
    $categoriePath .= $categorie['parent'].'/'.$categorie['categorie'];
}

$categoriePath .= '/';

// OK, now we have all Informations we can get from the Database

// Process the new Data
$tempFile = $_FILES['Filedata']['tmp_name'];
$targetFile = $categoriePath. $_FILES['Filedata']['name'];
$thumbFile = $categoriePath.'fg-thumbs/'.$_FILES['Filedata']['name'];
$allowedFileTypes = explode(',',$settings['extensions']);
$fileParts = pathinfo($_FILES['Filedata']['name']);

if(!in_array($fileParts['extension'], $allowedFileTypes)) {
    exit;
}

// File verschieben
move_uploaded_file($tempFile, $targetFile);
generateThumb($targetFile, $thumbFile, $settings['thumb_size'], 0, $settings['ratio'], 100, '999999');

// get DB infos
$sql = 'SELECT position FROM `'.TABLE_PREFIX.'mod_foldergallery_files` WHERE `parent_id`= '.$cat_id.' ORDER BY `position` DESC LIMIT 1;';
$query = $database->query($sql);
$result = $query->fetchRow();
$newPosition = $result['position'] +1;

//Insert to db
$sql = 'INSERT INTO `'.TABLE_PREFIX.'mod_foldergallery_files` (`parent_id`, `file_name`, `position`) VALUES ( \''.$cat_id.'\' , \''.$_FILES['Filedata']['name'].'\' , \''.$newPosition.'\');';
$query = $database->query($sql);

$sql = 'SELECT id FROM `'.TABLE_PREFIX.'mod_foldergallery_files` WHERE `parent_id` = '.$cat_id.' AND `position` = '.$newPosition.';';
$query = $database->query($sql);
$result = $query->fetchRow();


$newId = $result['id'];
$urlToThumb = str_replace(WB_PATH, WB_URL, $thumbFile);
$thumbEditLink = WB_URL."/modules/foldergallery/admin/modify_thumb.php?page_id=".$page_id."&section_id=".$section_id."&cat_id=".$cat_id."&id=".$newId;
$thumbEditAlt = $MOD_FOLDERGALLERY['THUMB_EDIT_ALT'] ;
$editThumbSource = THEME_URL.'/images/resize_16.png';
$imageDeleteLink = "javascript: confirm_link(\"".$MOD_FOLDERGALLERY['DELETE_ARE_YOU_SURE'] ."\", \"".WB_URL."/modules/foldergallery/admin/scripts/delete_img.php?page_id=".$page_id."&section_id=".$section_id."&cat_id=".$cat_id."&id=".$newId."\");";
$imageDeleteAlt = $MOD_FOLDERGALLERY['IMAGE_DELETE_ALT'];
$imageDeleteSource = THEME_URL.'/images/delete_16.png';


// Create output:
echo '
    <tr>
        <td align="center">
            <a href=\''.$thumbEditLink.'\'><img src="'.$urlToThumb.'"></a>
        </td>
        <td>
            '.$_FILES['Filedata']['name'].'
        </td>
        <td>
            <textarea cols="40" rows="3"  name="caption['.$newId.']" ></textarea>
	</td>
        <td align="center" width="20px">
            <a href=\''.$thumbEditLink.'\' title="'.$thumbEditAlt.'"><img src=\''.$editThumbSource.'\' border="0" alt="'.$thumbEditAlt.'"></a>
	</td>
	<td align="center" width="20px">
            <a href=\''.$imageDeleteLink.'\' title="'.$imageDeleteAlt.'"><img src=\''.$imageDeleteSource.'\' border="0" alt="'.$imageDeleteAlt.'"></a>
        </td>
    </tr>';

?>
