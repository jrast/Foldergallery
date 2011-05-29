<?php

require_once('../../../config.php');
require_once(WB_PATH.'/framework/functions.php');
require_once(WB_PATH.'/modules/admin.php');
require_once(WB_PATH.'/modules/foldergallery/info.php');
require_once(WB_PATH.'/modules/foldergallery/scripts/functions.php');
require_once(WB_PATH.'/modules/foldergallery/admin/scripts/backend.functions.php');
require_once(WB_PATH.'/modules/foldergallery/class/validator.php');

// Validate Data
$v = new Validator();
$v->setData($_POST);
$v->setKeys(array(
    'section_id'    => 'integer',
    'page_id'       => 'integer',
    'cat_parent'    => 'string',
    'folder_name'   => 'string',
    'cat_title'     => 'string',
    'cat_desc'      => 'string',
));
$v->process();
$request = $v->getValidData();
// This is used to prevent SQL attacks
$request['cat_parent']  = $admin->add_slashes($request['cat_parent']);
$request['folder_name'] = $v->getSaveFilename($request['folder_name']);
$request['cat_title']   = $admin->add_slashes($request['cat_title']);
$request['cat_desc']    = $admin->add_slashes($request['cat_desc']);


// Get the settings for this section
$settings = getSettings($section_id);

// Check if Parent Directory exists
if($request['cat_parent'] == '/') {
    $request['cat_parent'] = '';
}
$parent_dir = WB_PATH.$settings['root_dir'].$request['cat_parent'];
if(!is_dir($parent_dir)) {
    die('A Error occured during creating a new directory!');
}
// Check if new Directory does not allready exist
if($request['folder_name'] == '') {
    die('A Error occured during creating a new directory!');
}
$new_dir = $parent_dir.'/'.$request['folder_name'];
if(is_dir($new_dir)) {
    die('Choose another folder name, this one allready exists!');
}
make_dir($new_dir);

// get the parent id from the database
$sql = 'SELECT id, niveau, childs FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE section_id='.$request['section_id']
       .' AND CONCAT(`parent`,\'/\', `categorie`) = \''.$request['cat_parent'].'\';';
$query = $database->query($sql);
$result = $query->fetchRow();
$parent_id = (int) $result['id'];
$niveau = $result['niveau'] + 1;
$childs = explode(',',$result['childs']);


// OK, prepare the Insert SQL:
$sql = 'INSERT INTO '.TABLE_PREFIX.'mod_foldergallery_categories (`section_id`, `parent_id`, `parent`, `categorie`, `cat_name`, `description`, `is_empty`, `niveau`, `active`) '
    .' VALUES ('.$request['section_id'].', '.$parent_id.', \''.$request['cat_parent'].'\', \''.$request['folder_name'].'\', \''.$request['cat_title'].'\' ,\''.$request['cat_desc'].'\', 0, '.$niveau.', 0);';
$database->query($sql);

// OK, now update the parent_categorie
$sql = 'SELECT id FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE `section_id`='.$request['section_id']
      .' AND CONCAT(`parent`, \'/\', `categorie`) = \''.$request['cat_parent'].'/'.$request['folder_name'].'\';';
$query = $database->query($sql);
$result = $query->fetchRow();
$cat_id = $result['id'];
$childs[] = $cat_id;
$childs = implode(',',$childs);


$sql = 'UPDATE '.TABLE_PREFIX.'mod_foldergallery_categories '
       .'SET `has_child` = 1, `childs` = \''.$childs.'\' '
       .'WHERE `id` = '.$parent_id.';';
$database->query($sql);


$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);

$admin->print_footer();
?>
