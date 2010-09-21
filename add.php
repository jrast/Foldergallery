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
$database->query("INSERT INTO `" .TABLE_PREFIX ."mod_foldergallery_settings` "
		. "(`page_id`, `section_id`, `root_dir`, `extensions`) VALUES "
		. "('$page_id', '$section_id', '$root_dir', '$extensions')");
?>