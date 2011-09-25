<?php

require('../../../config.php');
require(WB_PATH . '/modules/admin.php');

// include the default language
require_once(WB_PATH .'/modules/foldergallery/languages/EN.php');
// check if module language file exists for the language set by the user (e.g. DE, EN)
if(file_exists(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php')) {
    require_once(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php');
}

// Files includen
require_once (WB_PATH . '/modules/foldergallery/info.php');
require_once (WB_PATH . '/modules/foldergallery/admin/scripts/backend.functions.php');
require_once (WB_PATH . '/modules/foldergallery/class/class.upload.php');
require_once (WB_PATH.'/modules/foldergallery/class/DirectoryHandler.Class.php');

//  Set the mySQL encoding to utf8
$oldMysqlEncoding = mysql_client_encoding();
mysql_set_charset('utf8',$database->db_handle);

$cat_id = $_GET['cat_id'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $settings = getSettings($section_id);
    $root_dir = $settings['root_dir']; //Chio

    $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'mod_foldergallery_files WHERE id=' . $_GET['id'] . ';';
    if ($query = $database->query($sql)) {
        $result = $query->fetchRow();
        $bildfilename = $result['file_name'];
        $parent_id = $result['parent_id'];


        $query2 = $database->query('SELECT * FROM ' . TABLE_PREFIX . 'mod_foldergallery_categories WHERE id=' . $parent_id . ' LIMIT 1;');
        $categorie = $query2->fetchRow();
        if ($categorie['parent'] != "-1") {
            $parent = $categorie['parent'] . '/' . $categorie['categorie'];
        }
        else {
            $parent = '';
        }
        $full_file_link = $url . $root_dir . $parent . '/' . $bildfilename;
        $full_file = $path . $root_dir . $parent . '/' . $bildfilename;
        $thumbFolder = $path.$root_dir.$parent.$thumbdir.'/';
        $thumb_file = $thumbFolder.$bildfilename;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Löscht das bisherige Thumbnail
            deleteFile($thumb_file);

            //Create the new Thumb
            $handle = new upload(DirectoryHandler::DecodePath($full_file));
            FG_appendThumbSettings($handle, $settings['tbSettings'], DirectoryHandler::DecodePath($bildfilename));
            $topCrop = floor($_POST['y1']);
            $rightCrop = floor($handle->image_src_x - $_POST['x2']);
            $bottomCrop = floor($handle->image_src_y - $_POST['y2']);
            $leftCrop = floor($_POST['x1']);
            $handle->image_precrop = "$topCrop $rightCrop $bottomCrop $leftCrop";
            $handle->process(DirectoryHandler::DecodePath($thumbFolder));
            if($handle->processed) {
                $admin->print_success($MOD_FOLDERGALLERY['UPDATED_THUMB'], WB_URL . '/modules/foldergallery/admin/modify_cat.php?page_id=' . $page_id . '&section_id=' . $section_id . '&cat_id=' . $cat_id);
            }
            else {
                $admin->print_error("Could not create a new thumbnail!", WB_URL . '/modules/foldergallery/admin/modify_cat.php?page_id=' . $page_id . '&section_id=' . $section_id . '&cat_id=' . $cat_id);
            }
        } else {
            list($width, $height, $type, $attr) = getimagesize(DirectoryHandler::DecodePath($full_file));
            $previewWidth = $settings['tbSettings']['image_x'];
            $previewHeight = $settings['tbSettings']['image_y'];

            $t = new Template(dirname(__FILE__) . '/templates', 'remove');
            $t->set_file('modify_thumb', 'modify_thumb.htt');
            // clear the comment-block, if present
            $t->set_block('modify_thumb', 'CommentDoc');
            $t->clear_var('CommentDoc');

            $t->set_var(array(
                // Infos for JCrop
                'REL_WIDTH'         => $width,
                'REL_HEIGHT'        => $height,
                'THUMB_SIZE'        => $previewWidth,
                'RATIO'             => $previewWidth/$previewHeight,
                // Language Strings
                'EDIT_THUMB'        => $MOD_FOLDERGALLERY['EDIT_THUMB'],
                'EDIT_THUMB_DESCR'  => $MOD_FOLDERGALLERY['EDIT_THUMB_DESCRIPTION'],
                'SAVE_NEW_THUMB'    => $MOD_FOLDERGALLERY['EDIT_THUMB_BUTTON'],
                'CANCEL'            => $TEXT['CANCEL'],
                // Data about the Image and Preview
                'FULL_FILE_LINK'    => $full_file_link,
                'PREVIEW_HEIGHT'    => $previewHeight,
                'PREVIEW_WIDTH'     => $previewWidth,
                'WB_URL'            => WB_URL,
                'PAGE_ID'           => $page_id,
                'SECTION_ID'        => $section_id,
                'CAT_ID'            => $cat_id,
                'IMG_ID'            => $_GET['id']
            ));

            $t->pparse('output', 'modify_thumb');
        }
    }
} else {
    $admin->print_error($MOD_FOLDERGALLERY['ERROR_MESSAGE'], WB_URL . '/modules/foldergallery/admin/modify_cat.php?page_id=' . $page_id . '&section_id=' . $section_id . '&cat_id=' . $cat_id);
}
$admin->print_footer();

// reset the mySQL encoding
mysql_set_charset($oldMysqlEncoding, $database->db_handle);
?>
