<?php

require_once('../../../../config.php');
if(defined('WB_PATH') == false) {
    exit();
}

$fileArray = array();
foreach ($_POST as $key => $value) {
	if ($key != 'folder') {
		if (file_exists(WB_PATH . $_POST['folder'] . '/' . $value)) {
			$fileArray[$key] = $value;
		}
	}
}
echo json_encode($fileArray);

?>
