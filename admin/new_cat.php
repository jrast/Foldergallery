<?php

require_once('../../../config.php');
require_once(WB_PATH.'/modules/admin.php');
require_once(WB_PATH.'/modules/foldergallery/info.php');
require_once(WB_PATH.'/modules/foldergallery/scripts/functions.php');
require_once(WB_PATH.'/modules/foldergallery/admin/scripts/backend.functions.php');

// include the default language
require_once(WB_PATH .'/modules/foldergallery/languages/EN.php');
// check if module language file exists for the language set by the user (e.g. DE, EN)
if(file_exists(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php')) {
    require_once(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php');
}

// Get all Folders in this gallery
$settings = getSettings($section_id);
$invisibleFileNames = array_merge($invisibleFileNames, $wbCoreFolders);
$folders = getFolderData($path.$settings['root_dir'], array(), $invisibleFileNames, 2);

// Template
$t = new Template(dirname(__FILE__).'/templates', 'remove');
$t->halt_on_error = 'no';
$t->set_file('new_cat', 'new_cat.htt');
// clear the comment-block, if present
$t->set_block('new_cat', 'CommentDoc'); $t->clear_var('CommentDoc');
$t->set_block('new_cat', 'ordner_select', 'ORDNER_SELECT');


// set language strings
$t->set_var(array(
    'NEW_CAT_TITLE'         => $MOD_FOLDERGALLERY['NEW_CAT'],
    'CAT_PARENT_STRING'     => $MOD_FOLDERGALLERY['CAT_PARENT'],
    'FOLDER_NAME_STRING'    => $MOD_FOLDERGALLERY['FOLDER_NAME'],
    'CAT_TITLE_STRING'      => $MOD_FOLDERGALLERY['CAT_TITLE'],
    'CAT_DESC_STRING'       => $MOD_FOLDERGALLERY['CAT_DESC'],
    'SAVE_STRING'           => $TEXT['SAVE'],
    'CANCEL_STRING'         => $TEXT['CANCEL']
));

// parent folder Select
$t->set_var('ORDNER', '/');
$t->parse('ORDNER_SELECT', 'ordner_select', true);
foreach($folders as $folder) {
    $t->set_var('ORDNER', $folder);
    $t->parse('ORDNER_SELECT', 'ordner_select', true);
}


// set the links and other actions
$t->set_var(array(
    'SECTION_ID_VALUE'  => $section_id,
    'PAGE_ID_VALUE'     => $page_id,
    'NEW_CAT_LINK'      => WB_URL.'/modules/foldergallery/admin/save_new_cat.php?page_id='.$page_id.'&section_id='.$section_id,
    'CANCEL_ONCLICK'    => 'javascript: window.location = \''.ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'\';'
));

$t->pparse('Output', 'new_cat');
$admin->print_footer();
?>
