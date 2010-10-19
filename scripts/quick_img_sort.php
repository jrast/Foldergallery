<?php
require('../../../config.php');
if(defined('WB_PATH') == false) { exit("Cannot access this file directly");  }

if(isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])) {
	$cat_id = $_GET['cat_id'];
} else {
	$error['no_cat_id'] = 1;
}

if(isset($_GET['sort'])) {
	switch($_GET['sort']) {
		case "ASC": 
			$sort = "ASC";
			break;
		case "DESC":
			$sort = "DESC";
		default:
			$error['wrong_sort'] = 1;
			break;
	}
}
?>