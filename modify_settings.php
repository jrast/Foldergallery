<?php

// Admin Backend erstellen
require('../../config.php');
require(WB_PATH.'/modules/admin.php');

// check if backend.css file needs to be included into <body></body>
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/foldergallery/backend.css")) {
echo '<style type="text/css">';
include(WB_PATH .'/modules/foldergallery/backend.css');
echo "\n</style>\n";
}
// check if backend.js file needs to be included into <body></body>
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/foldergaller/backend.js")) {
echo '<script type="text/javascript">';
include(WB_PATH .'/modules/foldergallery/backend.js');
echo "</script>";
}

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php')) {
// no module language file exists for the language set by the user, include default module language file DE.php
require_once(WB_PATH .'/modules/foldergallery/languages/DE.php');
} else {
// a module language file exists for the language defined by the user, load it
require_once(WB_PATH .'/modules/foldergallery/languages/'.LANGUAGE .'.php');
}

// Files includen
require_once (WB_PATH.'/modules/foldergallery/info.php');
require_once (WB_PATH.'/modules/foldergallery/scripts/backend.functions.php');

// --- jQueryAdmin / LibraryAdmin Integration; last edited 27.01.2011 ---
$jqa_lightboxes = array();
if ( file_exists( WB_PATH.'/modules/libraryadmin/foldergallery_include.php' ) ) {
    include_once WB_PATH.'/modules/libraryadmin/foldergallery_include.php';
    $jqa_lightboxes = get_lightboxes();
}
elseif ( file_exists( WB_PATH.'/modules/jqueryadmin/foldergallery_include.php' ) ) {
    include_once WB_PATH.'/modules/jqueryadmin/foldergallery_include.php';
    $jqa_lightboxes = get_lightboxes();
}
// --- end jQueryAdmin / LibraryAdmin Integration ---

// Einstellungen zur aktuellen Foldergallery aus der DB
$settings = getSettings($section_id);

// Template
$t = new Template(dirname(__FILE__).'/templates', 'remove');
$t->halt_on_error = 'no';
$t->set_file('modify_settings', 'modify_settings.htt');
// clear the comment-block, if present
$t->set_block('modify_settings', 'CommentDoc'); $t->clear_var('CommentDoc');

$t->set_block('modify_settings', 'ordner_select', 'ORDNER_SELECT');

$t->set_block('modify_settings', 'ratio_select', 'RATIO_SELECT');

// find lightbox files in template folder
$lightbox_select = '<select name="lightbox" id="lightbox">';
if ( $dh = opendir(dirname(__FILE__).'/templates') ) {
    while ( ($file = readdir($dh)) !== false ) {
        if ( preg_match( "/^view_(\w+).htt$/", $file, $matches ) ) {
            $lightbox_select .= '<option value="'
                             .  $matches[1] .'"';
            if ( $matches[1] == $settings['lightbox'] ) {
                $lightbox_select .= ' selected="selected"';
            }
            $lightbox_select .= '>'
                             .  $matches[1]
                             .  '</option>';
        }
    }
    closedir($dh);
}

// ----- jQueryAdmin / LibraryAdmin Integration; last edited 27.01.2011 -----
if ( count( $jqa_lightboxes ) > 0 ) {
    foreach ( $jqa_lightboxes as $i => $lb ) {
        if ( is_array( $lb ) ) {
            foreach( $lb as $item ) {
                $lightbox_select .= '<option value="'.$i.'/plugins/'.$item.'"';
                if ( $i.'/plugins/'.$item == $settings['lightbox'] ) {
                    $lightbox_select .= ' selected="selected"';
                }
                $lightbox_select .= '> ' . $i . ': '
                                 .  $item
                                 .  '</option>';
            }
        }
        else {
            $lightbox_select .= '<option value="'.$lb.'"';
            if ( $lb == $settings['lightbox'] ) {
                $lightbox_select .= ' selected="selected"';
            }
            $lightbox_select .= '> jQueryAdmin: '
                             .  $lb
                             .  '</option>';
        }
    }
}
// ----- end jQueryAdmin / LibraryAdmin Integration -----

$lightbox_select .= '</select>';

$catpicselect = '<select name="catpic">';
$catpicselect .= '<option value="0"'; if ($settings['catpic'] == 0) {$catpicselect .= ' selected="selected"';} $catpicselect .= '>Random</option>';
$catpicselect .= '<option value="1"'; if ($settings['catpic'] == 1) {$catpicselect .= ' selected="selected"';} $catpicselect .= '>First</option>';
$catpicselect .= '<option value="2"'; if ($settings['catpic'] == 2) {$catpicselect .= ' selected="selected"';} $catpicselect .= '>Last</option>';
$catpicselect .= '</select>';

// Text einsetzten
$t->set_var(array(
        'PAGE_ID_VALUE'                 => $page_id,
        'SECTION_ID_VALUE'              => $section_id,
	'SETTINGS_STRING'		=> $MOD_FOLDERGALLERY['SETTINGS'],
	'ROOT_FOLDER_STRING'            => $MOD_FOLDERGALLERY['ROOT_DIR'],
	'EXTENSIONS_STRING'		=> $MOD_FOLDERGALLERY['EXTENSIONS'],
	'EXTENSIONS_VALUE'		=> $settings['extensions'],
	'INVISIBLE_STRING'		=> $MOD_FOLDERGALLERY['INVISIBLE'],
	'INVISIBLE_VALUE'		=> $settings['invisible'],
	'SAVE_STRING' 			=> $TEXT['SAVE'],
	'CANCEL_STRING' 		=> $TEXT['CANCEL'],
	'PICS_PP_STRING'    		=> $MOD_FOLDERGALLERY['PICS_PP'],
	'PICS_PP_VALUE'    		=> $settings['pics_pp'],
	'THUMBSIZE'    			=> $settings['thumb_size'],
	'THUMB_SIZE_STRING'   		=> $MOD_FOLDERGALLERY['THUMB_SIZE'],
	'THUMB_RATIO_STRING'    	=> $MOD_FOLDERGALLERY['THUMB_RATIO'],
	'THUMB_NOT_NEW_STRING'    	=> $MOD_FOLDERGALLERY['THUMB_NOT_NEW'],
	'CHANGING_INFO_STRING'    	=> $MOD_FOLDERGALLERY['CHANGING_INFO'],
	'LIGHTBOX_STRING' 		=> $MOD_FOLDERGALLERY['LIGHTBOX'],
	'LIGHTBOX_VALUE' 		=> $lightbox_select,
	'CATPIC_VALUE' 			=> $catpicselect,
));

// Links einsetzen
$t->set_var(array(
	'CANCEL_ONCLICK'        => 'javascript: window.location = \''.ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'\';',
	'MODIFY_SETTINGS_LINK'	=> WB_URL.'/modules/foldergallery/save_settings.php?page_id='.$page_id.'&section_id='.$section_id
));

//Tooltips einsetzen
$t->set_var(array(
	'ROOT_FOLDER_STRING_TT' => $MOD_FOLDERGALLERY['ROOT_FOLDER_STRING_TT'],
	'EXTENSIONS_STRING_TT'	=> $MOD_FOLDERGALLERY['EXTENSIONS_STRING_TT'],
	'INVISIBLE_STRING_TT'	=> $MOD_FOLDERGALLERY['INVISIBLE_STRING_TT'],
));

if ( ! empty( $settings['invisible'] ) ) {
    $invisibleFileNames = array_merge( $invisibleFileNames, explode( ',', $settings['invisible'] ) );
}

// WB Systemordner sollen nicht angezeigt werden
$invisibleFileNames = array_merge($invisibleFileNames, $wbCoreFolders);

// Ordnerauswahl für den Root Folder erstellen
$ordnerliste = getFolderData($path, array(), $invisibleFileNames, 2);

foreach($ordnerliste as $ordner) {
	$t->set_var('ORDNER', $ordner);
	if($ordner != $settings['root_dir']) {
		$t->set_var('SELECTED','');
	} else {
		$t->set_var('SELECTED','selected="selected"');
	}
	$t->parse('ORDNER_SELECT', 'ordner_select', true);
}

//Ratio Auswahlliste
$ratioArray = array("quadratisch" => 1, "4:3" => round(4/3, 4), "3:4" => round(3/4, 4), "16:9" => round(16/9, 4), "9:16" => round(9/16, 4));
foreach($ratioArray as $ratio => $value) {
	$t->set_var(array(
			'RATIO'		=> $ratio,
			'RATIO_VALUE'	=> $value
			));
	if($value == $settings['ratio']) {
		$t->set_var('SELECTED','selected="selected"');
	} else {
		$t->set_var('SELECTED','');
	}
	$t->parse('RATIO_SELECT', 'ratio_select', true);
}

$t->pparse('Output', 'modify_settings');

$admin->print_footer();
?>
