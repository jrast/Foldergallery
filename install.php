<?php
/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: index.php'));  

/*
 * Delete existing tables
 */
$database->query("DROP TABLE IF EXISTS `" .TABLE_PREFIX ."mod_foldergallery_settings`");
$database->query("DROP TABLE IF EXISTS `" .TABLE_PREFIX ."mod_foldergallery_files`");
$database->query("DROP TABLE IF EXISTS `" .TABLE_PREFIX ."mod_foldergallery_categories`");

// create a new, clean module DB-table)

$sql = 'CREATE TABLE `' .TABLE_PREFIX .'mod_foldergallery_settings` ( '
	. '`section_id` INT NOT NULL DEFAULT \'0\','
	. '`page_id` INT NOT NULL DEFAULT \'0\','
	. '`root_dir` VARCHAR(255) NOT NULL DEFAULT \'\','
	. '`extensions` VARCHAR(255) NOT NULL DEFAULT \'\','
	. '`invisible` VARCHAR( 255 ) NOT NULL DEFAULT \'\','
	. '`background` VARCHAR(50) NOT NULL DEFAULT \'#FFFFFF\','
	. '`thumb_size` INT NOT NULL DEFAULT \'150\','
	. '`ratio` VARCHAR(10) NOT NULL DEFAULT \'\','
	. '`pics_pp` INT NOT NULL DEFAULT \'15\','
	. '`lightbox` VARCHAR(50) NOT NULL DEFAULT \'shutter\','
	. '`catpic` TINYINT NOT NULL DEFAULT \'0\','
	. 'PRIMARY KEY (section_id)'
	. ' );';
$database->query($sql);

$sql = 'CREATE TABLE `'.TABLE_PREFIX .'mod_foldergallery_files` ( '
	.'`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,'
	.'`parent_id` INT NOT NULL DEFAULT \'0\','
	.'`file_name` VARCHAR(255) NOT NULL DEFAULT \'\','
	.'`position` INT NOT NULL DEFAULT \'0\', '
	.'`caption` TEXT NOT NULL DEFAULT \'\');';
$database->query($sql);

$sql = 'CREATE TABLE `'.TABLE_PREFIX.'mod_foldergallery_categories` ( '
	.'`id` INT AUTO_INCREMENT,'
	.'`section_id` INT NOT NULL DEFAULT \'0\','
	.'`parent_id` INT NOT NULL DEFAULT \'0\','
	.'`categorie` VARCHAR(255) NOT NULL DEFAULT \'\','
	.'`parent` VARCHAR(255) NOT NULL DEFAULT \'\','
	.'`cat_name` VARCHAR(255) NOT NULL DEFAULT \'\','
	.'`active` TINYINT NOT NULL DEFAULT \'1\','
	.'`is_empty` INT NOT NULL DEFAULT \'1\','
	.'`position`INT NOT NULL DEFAULT \'0\','
	.'`niveau` INT NOT NULL DEFAULT \'-1\','
	.'`has_child` INT NOT NULL DEFAULT \'0\','
	.'`childs` VARCHAR(255) NOT NULL DEFAULT \'\','
	.'`description` VARCHAR(255) NOT NULL DEFAULT \'\','
	.'PRIMARY KEY (id));';
$database->query($sql);
?>
