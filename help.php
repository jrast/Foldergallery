<?php

require('../../config.php');
if (defined('WB_PATH') == false) {
    exit("Cannot access this file directly");
}
require(WB_PATH . '/modules/admin.php');

// check if module language file exists for the language set by the user (e.g. DE, EN)
if (!file_exists(WB_PATH . '/modules/foldergallery/help/' . LANGUAGE . '.php')) {
// no module language file exists for the language set by the user, include default module language file EN.php
    require_once(WB_PATH . '/modules/foldergallery/help/EN.php');
} else {
// a module language file exists for the language defined by the user, load it
    require_once(WB_PATH . '/modules/foldergallery/help/' . LANGUAGE . '.php');
}

$error = array();

// Check for updates!
require_once (WB_PATH.'/modules/foldergallery/info.php');
$versionFeedback = '';
$newVersion = @file_get_contents("http://www.foldergallery.ch/version_info.php");
if($newVersion == false || strlen($newVersion) > 10) {
    $error['version_check'] = $FG_HELP['VERSION_CHECK_ERROR'];
} else {
    $newVersionSplit = explode('.', $newVersion);
    $installedVersionSplit = explode('.', $module_version);
    if(count($newVersionSplit) == count($installedVersionSplit)) {
        $versionFeedback = $FG_HELP['NOUPDATES'];
        for($i = 0; $i < 2; $i++){
            if($newVersionSplit[$i] > $installedVersionSplit[$i]) {
                $versionFeedback = sprintf($FG_HELP['UPDATE'],$newVersion);
                break;
            }
        }
    } else {
        $error['version_compare'] = $FG_HELP['VERSION_COMPARE_ERROR'];
    }
}

//Template
$t = new Template(dirname(__FILE__).'/help', 'remove');
$t->set_file('help', 'help.htt');
// clear the comment-block, if present
$t->set_block('help', 'CommentDoc'); $t->clear_var('CommentDoc');

// Version
$t->set_block('help', 'version_error', 'VersionError');
if(key_exists('version_compare', $error) || key_exists('version_check', $error)) {
    if(key_exists('version_compare', $error)) {
        $t->set_var(array(
            'VERSION_ERROR' => $error['version_compare']
        ));
    } else {
        $t->set_var(array(
            'VERSION_ERROR' => $error['version_check']
        ));
    }
    $t->parse('VersionError', 'version_error', true);
} else {
    $t->clear_var('VersionError');
}

$t->set_var(array(
    'TITLE'                 => $FG_HELP['TITLE'],
    'VERSION'               => $FG_HELP['VERSION'],
    'YOUR_VERSION_TEXT'     => sprintf($FG_HELP['YOUR_VERSION'],$module_version),
    'NEWER_VERSION_TEXT'    => $versionFeedback,
    'VERSION_TEXT'          => $FG_HELP['VERSION_TEXT'],
    'HOMEPAGE_TEXT'         => $FG_HELP['HOMEPAGE_TEXT'],
    'HELP_TITLE'            => $FG_HELP['HELP_TITLE'],
    'HELP_TEXT'             => $FG_HELP['HELP_TEXT'],
    'BUG_TITLE'             => $FG_HELP['BUG_TITLE'],
    'BUG_TEXT'              => $FG_HELP['BUG_TEXT'],
    'BACK_STRING'           => $FG_HELP['BACK_STRING'],
    'BACK_ONCLICK'          => 'javascript: window.location = \''.ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'\';'
));

$t->pparse('output', 'help');


$admin->print_footer();
?>
