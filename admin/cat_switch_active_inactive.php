<?php
require('../../../config.php');
if(defined('WB_PATH') == false) { exit("Cannot access this file directly");  }

// check if module language file exists for the language set by the user (e.g. DE, EN)
if (!file_exists(WB_PATH . '/modules/foldergallery/languages/' . LANGUAGE . '.php')) {
    // no module language file exists for the language set by the user, include default module language file EN.php
    require_once(WB_PATH . '/modules/foldergallery/languages/EN.php');
} else {
    // a module language file exists for the language defined by the user, load it
    require_once(WB_PATH . '/modules/foldergallery/languages/' . LANGUAGE . '.php');
}

// Answer array wich is sent back to the backend
$answer = array();
$answer['message'] = $MOD_FOLDERGALLERY['CAT_TOGGLE_ACTIV_FAIL'];
$answer['success'] = 'false';

// Check the Parameters
if(!isset($_POST['cat_id']) || !isset($_POST['action'])) {
    exit(json_encode($answer));
}

if(!(($_POST['action'] == 'enable') || ($_POST['action'] == 'disable')) || !is_numeric($_POST['cat_id'])) {
    exit(json_encode($answer));
}
// OK, Parameters seem to be save

// Check if user has enough rights to do this:
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Modules', 'module_view', false, false);
if (!($admin->is_authenticated() && $admin->get_permission('foldergallery', 'module'))) {
    exit(json_encode($answer));
}


if($_POST['action'] == 'disable') {
    $active = 0;
    $answer['message'] = $MOD_FOLDERGALLERY['CAT_INACTIVE'];
} else {
    $active = 1;
    $answer['message'] = $MOD_FOLDERGALLERY['CAT_ACTIVE'];
}

$database->query("UPDATE `".TABLE_PREFIX."mod_foldergallery_categories` SET active = ".$active." WHERE `id` = ".$_POST['cat_id']);

if($databse->is_error()) {
    $answer['message'] = $MOD_FOLDERGALLERY['CAT_TOGGLE_ACTIV_FAIL'];
    exit(json_encode($answer));
}

// If the script is still running, set success to true
$answer['success'] = 'true';
// and echo the answer as json to the ajax function
echo json_encode($answer);
?>
