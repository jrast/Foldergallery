<?php

// prevent this file from being accessed directly
if (!defined('WB_PATH'))
    die(header('Location: index.php'));

global $database;

// Upate from 1.20 to next Version:
// Save the old Settings
$sql = "SELECT * FROM `" . TABLE_PREFIX . "mod_foldergallery_settings`";
$query = $database->query($sql);
while ($row = $query->fetchRow()) {
    $settings[] = $row;
}

// Delete the old Settings Table
$database->query("DROP TABLE IF EXISTS `" . TABLE_PREFIX . "mod_foldergallery_settings`;");

// Create the new Settings Table
$sql = "CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mod_foldergallery_settings` ("
        . "`id` int(11) NOT NULL AUTO_INCREMENT,"
        . "`section_id` int(11) NOT NULL,"
        . "`s_name` varchar(100) NOT NULL,"
        . "`s_value` TEXT NOT NULL,"
        . "PRIMARY KEY (`id`));";
$database->query($sql);

foreach($settings as $value) {
    foreach($value as $key => $val) {
        $section_id = $value['section_id'];
        $sql = "INSERT INTO `".TABLE_PREFIX."mod_foldergallery_settings` (`id`, `section_id`, `s_name`, `s_value`) VALUES "
            ."(null, '".$section_id."', '".$key."', '".$val."');";
        $database->query($sql);
    }
}


?>