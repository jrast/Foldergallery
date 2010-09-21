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

// Delete DB-Entries (messages and settings)
$sql = 'SELECT `parent` FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE section_id='.$section_id.';';
$query = $database->query($sql);
while($cat = $query->fetchRow()) {
	$sql = 'DELETE FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE parent_id='.$cat['parent'];
	$database->query($sql);
}	
$database->query("DELETE FROM `".TABLE_PREFIX."mod_foldergallery_settings` WHERE `page_id` = '$page_id' AND `section_id` = '$section_id'");
$database->query("DELETE FROM `".TABLE_PREFIX."mod_foldergallery_categories` WHERE `section_id` = '$section_id'");


