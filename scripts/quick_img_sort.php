<?php
require('../../../config.php');

$error = null;

if(isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])) {
	$cat_id = $_GET['cat_id'];
} else {
	$error['no_cat_id'] = 1;
}

if(isset($_GET['page_id']) && is_numeric($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
} else {
	$error['no_page_id'] = 1;
}

if(isset($_GET['section_id']) && is_numeric($_GET['section_id'])) {
	$section_id = $_GET['section_id'];
} else {
	$error['no_section_id'] = 1;
}

if(isset($_GET['sort'])) {
	switch($_GET['sort']) {
		case "ASC":
			$sort = "ASC";
			break;
		case "DESC":
			$sort = "DESC";
			break;
		default:
			$error['no_sort'] = 1;
			break;
	}

}

if($error != null) {
	header("Location: ../../index.php");
	exit();
}

// Create new admin object and print admin header
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_settings');


$sql="SELECT file_name, position, id FROM `".TABLE_PREFIX."mod_foldergallery_files` WHERE parent_id =".$cat_id." ORDER BY file_name ".$sort;

$query=$database->query($sql);

if($query->numRows()) {
	$sql = "UPDATE `".TABLE_PREFIX."mod_foldergallery_files` SET position= CASE ";
	$position = 1;
	while($result = $query->fetchRow()){
		$sql = $sql."WHEN id=".$result['id']." THEN '".$position."' ";
		$position++;
	}
	$sql = $sql." ELSE position END;";
}


if($database->query($sql)){
	$admin->print_success($MESSAGE['PAGES']['REORDERED'],
	WB_URL.'/modules/foldergallery/modify_cat_sort.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id);
} else {
	$admin->print_error($TEXT['ERROR'],
	WB_URL.'/modules/foldergallery/modify_cat_sort.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id);
}

// Print admin footer
$admin->print_footer();
?>
