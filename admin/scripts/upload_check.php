<?php

require_once('../../../../config.php');
require_once(WB_PATH.'/modules/foldergallery/class/validator.php');

$v = new Validator();

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
