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

global $database;

$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_settings ADD lightbox VARCHAR(50) NOT NULL DEFAULT 'shutter'" );
$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_settings ADD catpic TINYINT NOT NULL DEFAULT '0'" );
$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_categories ADD active TINYINT NOT NULL DEFAULT '1'" );
$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_categories ADD childs VARCHAR(255) NOT NULL DEFAULT ''" );
//$database->query($sql);

//Eingef�gt von Chio:
$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_files ADD `parent_id` INT NOT NULL AFTER `file_name`");
$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_categories CHANGE `order` `position` INT( 11 ) NOT NULL DEFAULT '0' " );
$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_files CHANGE `order` `position` INT( 11 ) NOT NULL DEFAULT '0' " );
$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_settings DROP `order`  " );

$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_files DROP `section_id`  " );
$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_files DROP `file_link`  " );
$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_files DROP `thumb_link`  " );
$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_files DROP `parent`  " );

//Update auf 1.1 by Pumpi
$database->query("ALTER TABLE ".TABLE_PREFIX."mod_foldergallery_settings ADD `ratio` VARCHAR(10) NOT NULL DEFAULT '' AFTER `thumb_size`" );


// Ende CHio



?>