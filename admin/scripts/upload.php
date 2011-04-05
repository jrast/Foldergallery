<?php

require('../../../../config.php');
if(defined('WB_PATH') == false) { exit("Cannot access this file directly");  }

// check if module language file exists for the language set by the user (e.g. DE, EN)
if (!file_exists(WB_PATH . '/modules/foldergallery/languages/' . LANGUAGE . '.php')) {
    // no module language file exists for the language set by the user, include default module language file EN.php
    require_once(WB_PATH . '/modules/foldergallery/languages/EN.php');
} else {
    // a module language file exists for the language defined by the user, load it
    require_once(WB_PATH . '/modules/foldergallery/languages/' . LANGUAGE . '.php');
}

//if(!is_numeric($_POST['cat_id']) || !is_numeric($_POST['section_id']) || !is_numeric($_POST['page_id'])) {
//    die;
//}

//if (!empty($_FILES)) {
//	$tempFile = $_FILES['Filedata']['tmp_name'];
//	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
//	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
//
//	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
//	// $fileTypes  = str_replace(';','|',$fileTypes);
//	// $typesArray = split('\|',$fileTypes);
//	// $fileParts  = pathinfo($_FILES['Filedata']['name']);
//
//	// if (in_array($fileParts['extension'],$typesArray)) {
//		// Uncomment the following line if you want to make the directory if it doesn't exist
//		// mkdir(str_replace('//','/',$targetPath), 0755, true);
//
//		move_uploaded_file($tempFile,$targetFile);
//                echo '		<tr>
//			<input type="hidden" name="id[{COUNTER}]" value="{ID_VALUE}" alt="Thumb">
//			<td align="center">
//				<a href=\'{THUMB_EDIT_LINK}\'><img src="{IMAGE_VALUE}"></a>
//			</td>
//			<td>
//				{IMAGE_NAME_VALUE}
//			</td>
//			<td>
//				<textarea cols="40" rows="3"  name="caption[{COUNTER}]" >{CAPTION_VALUE}</textarea>
//			</td>
//			<td align="center" width="20px">
//				<a href=\'{THUMB_EDIT_LINK}\' title="{THUMB_EDIT_ALT}"><img src=\'{EDIT_THUMB_SOURCE}\' border="0" alt="{THUMB_EDIT_ALT}"></a>
//			</td>
//			<td align="center" width="20px">
//				<a href=\'{IMAGE_DELETE_LINK}\' title="{IMAGE_DELETE_ALT}"><img src=\'{DELETE_IMG_SOURCE}\' border="0" alt="{IMAGE_DELETE_ALT}"></a>
//			</td>
//		</tr>';
//		//echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
//	// } else {
//	// 	echo 'Invalid file type.';
//	// }
//}

echo "Erfolg";

?>
