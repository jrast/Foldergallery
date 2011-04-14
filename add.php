<?php
// Direkter Zugriff verhindern
if(!defined('WB_PATH')) die(header('Location: index.php'));  

/*
 * Neuer Eintrag in der DB erstellen
 * $root_dir wird dabei auf 'd41d8cd98f00b204e9800998ecf8427e' gesetzt,
 * damit überprüft werden kann, ob bereits ein Ordner festgelegt wurde
 * (Für interessierte: Es ist der MD5-Hashwert einer leeren Zeichenkette) 
 */
$root_dir = 'd41d8cd98f00b204e9800998ecf8427e';
$extensions = 'jpg,jpeg,gif,png';
$thumbSettings = array(
    'image_resize'  => true,
    'image_x'       => 150,
    'image_y'       => 150
);

$rawSql = "INSERT INTO `".TABLE_PREFIX."mod_foldergallery_settings` (`section_id`, `s_name`, `s_value`)"
    ." VALUES ('".$section_id."', '%s', '%s');";

$database->query(sprintf($rawSql, 'page_id', $page_id));
$database->query(sprintf($rawSql, 'root_dir', $root_dir));
$database->query(sprintf($rawSql, 'extensions', $extensions));
$database->query(sprintf($rawSql, 'invisible', ''));
$database->query(sprintf($rawSql, 'pics_pp', '15'));
$database->query(sprintf($rawSql, 'catpic', '0'));
$database->query(sprintf($rawSql, 'lightbox', 'shutter'));
$database->query(sprintf($rawSql, 'tbSettings', serialize($thumbSettings)));
?>
