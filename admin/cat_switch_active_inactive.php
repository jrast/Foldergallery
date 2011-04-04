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

$answer = array();

// Check the Parameters
if(!isset($_POST['cat_id']) || !isset($_POST['action'])) {
    $answer['success'] = 'false';
    $answer['message'] = 'Fehler beim aktivieren/deaktivieren der Kategorie! Ist dass ein Hack-Versuch?';
    exit(json_encode($answer));
}

if(!(($_POST['action'] == 'enable') || ($_POST['action'] == 'disable')) || !is_numeric($_POST['cat_id'])) {
    $answer['success'] = 'false';
    $answer['message'] = 'Fehler beim aktivieren/deaktivieren der Kategorie! Ist dass ein Hack-Versuch?';
    exit(json_encode($answer));
}

// OK, Parameters seem to be save

if($_POST['action'] == 'disable') {
    $active = 0;
} else {
    $active = 1;
}

$database->query("UPDATE `".TABLE_PREFIX."mod_foldergallery_categories` SET active = ".$active." WHERE `id` = ".$_POST['cat_id']);

$answer = array(
    'success'   => 'true',
    'message'   => 'Erfolgreich'
);

echo json_encode($answer);
?>
