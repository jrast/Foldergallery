<?PHP
// Direkter Zugriff verhindern
if (!defined('WB_PATH'))
    die(header('Location: index.php'));

function getSettings($section_id) {
    global $database;
    $sql = 'SELECT `s_name`, `s_value` FROM ' . TABLE_PREFIX . 'mod_foldergallery_settings WHERE '
            . '`section_id` = ' . $section_id;
    $query = $database->query($sql);
    while($row = $query->fetchRow()){
        $settings[$row['s_name']] = $row['s_value'];
    }
    $settings['section_id'] = $section_id;
    return $settings;
}

/**
 * Generiert ein Thumbnail $thumb aus $file, falls dieses noch nicht vorhanden ist
 * @return void or true
 * @param string $file  Pfadangabe zum original File
 * @param string $thumb Pfadangabe zum Thumbfile
 * **********************************************
 * Anpassung für individuelle Thumb erstellung
 * @param string $ratio Seitenverhältniss der Thumbs
 * @param string $positionX Position X von jCrop ansonsten 0
 * @param string $positionY Position Y von jCrop ansonsten 0
 * @param string $positionW Position W von jCrop ansonsten 0
 * @param string $positionH Position H von jCrop ansonsten 0
 */
function generateThumb($file, $thumb, $thumb_size, $showmessage, $ratio, $positionX = 0, $positionY = 0, $positionW = 0, $positionH = 0) {

    //Von Chio eingef�gt:
    global $megapixel_limit;
    if ($megapixel_limit < 2) {
        $megapixel_limit = 5;
    }

    static $thumbscounter, $thumbsstarttime, $allthumbssizes;
    if (!$thumbscounter) {
        $thumbscounter = 0;
    }
    if (!$thumbsstarttime) {
        $thumbsstarttime = time();
    }
    if (!$allthumbssizes) {
        $allthumbssizes = 0;
    }

    if (!is_file($file)) {
        if ($showmessage == 1) {
            echo '<b>Missing file:</b> ' . $file . '<br/>';
            return -1;
        }
    }

    $thumbscounter++;
    if ($thumbscounter == 80 AND $showmessage == 1)
        echo('<h3>stopped.. press F5 to reload</h3>');
    if ($thumbscounter > 80)
        return -1;

    $tgone = time() - $thumbsstarttime;
    if (time() - $thumbsstarttime > 50) {
        die('<h3>timeout.. press F5 to reload</h3>');
    }

    //if ($showmessage==1) {echo "<br/>creating thumb: ".$file;}
    // ENDE Chio
    // Einige Variablen
    $jpg = '\.jpg$|\.jpeg$';
    $gif = '\.gif$';
    $png = '\.png$';
    $fontSize = 2;
    $bg = "999999";

    $thumbFolder = dirname($thumb);


    //Verzeichnis erstellen, falls noch nicht vorhanden
    if (!is_dir($thumbFolder)) {
        $u = umask(0);
        if (!@mkdir($thumbFolder, 0777)) {
            echo '<!--p style="color: red; text-align: center;">Fehler beim Verzeichniss erstellen</p-->';
        }
        umask($u);
    }

    // Thumb erstellen
    if (!is_file($thumb)) {
        //checken, ob megapixel über 5:
        if (function_exists('getimagesize')) {
            list($width, $height, $type, $attr) = getimagesize($file);
            $fl = ceil(($width * $height) / 1000000);
            if ($fl > $megapixel_limit) {
                if ($showmessage == 1) {
                    echo '<br/><b>' . $fl . ' Megapixel; skipped!</b>';
                }
                return -2;
            }
        }


        if ($type == 2) {
            $original = @imagecreatefromjpeg($file);
        } elseif ($type == 1) {
            $original = @imagecreatefromgif($file);
        } elseif ($type == 3) {
            $original = @imagecreatefrompng($file);
        }

        if (isset($original)) {
            if (function_exists('getimagesize')) {
                list($width, $height, $type, $attr) = getimagesize($file);
            } else {
                continue;
            }


			//------------------------------------------------------------//
			//Werte berechnen:

			if (!isset($fullpercent)) {$fullpercent = 100;}

			//$thumb_size ist IMMER die Breite:
			$newwidth = $thumb_size;
			$newheight = $thumb_size/$ratio;

			if ($ratio < 1) {
			//portrait format:
				$newwidth = $thumb_size*$ratio;
				$newheight = $thumb_size;
			}


			$pic_ratio = $width / $height;
			if ($pic_ratio > $ratio) {
				//Bild ist breiter als der Rahmen erlaubt
				//echo '<p>breiter: ' .$pic_ratio.' '.$file.'</p>';

				$smallheight = $newheight;
				$smallwidth = $smallheight * $pic_ratio;
				$ofx = ($newwidth - $smallwidth) / 2;
				$ofy = 0;

				//values without crop:
				$smallwidth2 = $newwidth;
				$smallheight2 = $smallwidth2 / $pic_ratio;
				$ofx2 = 0;
				$ofy2 = ($newheight - $smallheight2) / 2;

			} else {
				//Bild ist hoeher als der Rahmen erlaubt
				//echo '<p>hoeher: ' .$pic_ratio.' '.$file.'</p>';

				$smallwidth = $newwidth;
				$smallheight = $smallwidth / $pic_ratio;
				$ofx = 0;
				$ofy = ($newheight - $smallheight) / 3; //Eher oberen Teil, dh /3

				//values without crop:
				$smallheight2 = $newheight;
				$smallwidth2 = $smallheight2 * $pic_ratio;
				$ofy2 = 0;
				$ofx2 = ($newwidth - $smallwidth2) / 2;
			}


			//mix crped and non-cropped values by percent:
			$f1 = 0.01 * $fullpercent;
			$f2 = 1.0 - $f1;
			$smallwidth = floor(($f1 * $smallwidth) + ($f2 * $smallwidth2));
			$smallheight = floor(($f1 * $smallheight) + ($f2 * $smallheight2));
			$ofx = floor(($f1 * $ofx) + ($f2 * $ofx2));
			$ofy = floor(($f1 * $ofy) + ($f2 * $ofy2));



			$newwidth = floor($newwidth);
			$newheight = floor($newheight);

			//Ausnahme: Bild ist kleiner als thumb
			if ($width <  $smallwidth AND $height <  $smallheight) {
				echo $smallwidth;
				$ofx = 0; $ofy = 0; $smallwidth = $width;  $smallheight = $height;
				$ofx = floor(($newwidth - $width) / 2);
				$ofy = floor(($newheight - $height) / 2);
			}

			//Ausnahme: Bild wird gecropt
			if (!empty($positionW) && !empty($positionH)) {
				$ofy = 0;
				$ofx = 0;
			}


            if (function_exists('imagecreatetruecolor')) {
                if ($ratio > 1) {
                	$small = imagecreatetruecolor($thumb_size, $thumb_size / $ratio);
               	} else {
                   	$small = imagecreatetruecolor($thumb_size * $ratio, $thumb_size);
                }

            } else {
                $small = imagecreate($smallwidth, $smallheight);
            }
            sscanf($bg, '%2x%2x%2x', $red, $green, $blue);
            $b = imagecolorallocate($small, $red, $green, $blue);
            imagefill($small, 0, 0, $b);
            if ($original) {
                //Änderungen der Variablen die für JCrop Thumberstellung anderst sein müssen
                if (!empty($positionW) && !empty($positionH)) {
                    $width = $positionW;
                    $height = $positionH;

                    //wenn ein Ratio eingestellt ist werden die small Atribute des Thumbs angepasst
                    //die ist allerdings nur bei JCrop nötig normal wird die größe vom 0Punkt aus errechnet

                    if ($ratio > 1) {
                        $smallwidth = $thumb_size;
                        $smallheight = $thumb_size / $ratio;
                    } else {
                        $smallwidth = $thumb_size * $ratio;
                        $smallheight = $thumb_size;
                    }

                }

                if (function_exists('imagecopyresampled')) {
                    imagecopyresampled($small, $original, $ofx, $ofy, $positionX, $positionY, $smallwidth, $smallheight, $width, $height);
                } else {
                    imagecopyresized($small, $original, $ofx, $ofy, $positionX, $positionY, $smallwidth, $smallheight, $width, $height);
                }
            } else {
                $black = imagecolorallocate($small, 0, 0, 0);
                $fw = imagefontwidth($fontSize);
                $fh = imagefontheight($fontSize);
                $htw = ($fw * strlen($filename)) / 2;
                $hts = $thumb_size / 2;
                imagestring($small, $fontSize, $hts - $htw, $hts - ($fh / 2), $filename, $black);
                imagerectangle($small, $hts - $htw - $fw - 1, $hts - $fh, $hts + $htw + $fw - 1, $hts + $fh, $black);
            }
            imagejpeg($small, $thumb);
            imagedestroy($original);
            imagedestroy($small);
            return true;
        }
    }
}

function FG_cleanCat($string) {
    if (is_string($string)) {
        if (!preg_match('~^(/([.a-zA-Z0-9_-]/{0,1})*[^/])$~', $string)) {
            return '';
        } else {
            return $string;
        }
    } else {
        return '';
    }
}

/**
 * Gibt die Kategorie ID der Kategorie zurück
 *
 * @global $database
 * @param int $sectionID WB section_id
 * @param string $kategorie String wie er im URL angezeigt wird
 * @return int
 */
function FG_getCatId($sectionID, $kategorie) {
    global $database;
    if($kategorie == '') {
        $kategorie = '-1/Root';
    }
    $sql = "SELECT id FROM ".TABLE_PREFIX."mod_foldergallery_categories WHERE ".
            "section_id = ".$sectionID." AND is_empty = 0 AND active = 1 ".
            "AND CONCAT(parent,'/',categorie) = '".$kategorie."'";
    $query = $database->query($sql);
    $ergebnis = $query->fetchRow();
    if(!$ergebnis) {
        throw new Exception('Kategorie nicht vorhanden!', 001);
    }
    $katID = $ergebnis['id'];
    return $katID;
}


function display_categories($parent_id, $section_id , $tiefe = 0) {
	$padding = $tiefe*20;
	global $database;
	global $url;
	global $page_id;
        global $MOD_FOLDERGALLERY;
	$list = "\n";
	$sql = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE parent_id='.$parent_id.' AND section_id ='.$section_id.' ORDER BY `position` ASC;';
	$query = $database->query($sql);
	$zagl = $query->numRows();


	$arrup = false;
	$arrdown = true;
	if ($zagl > 1) {}

	$counter = 0;
	while($result = $query->fetchRow()){
		$counter ++;
		if ($counter > 1) {$arrup = true;}
		if ($counter == $zagl) {$arrdown = false;}

		if ($parent_id != "-1") $cursor = ' cursor: move;';
		else $cursor = '';

		if($result['has_child']){
			$list .= "<li id='recordsArray_".$result['id']."' style='padding: 1px 0px 1px 0px;".$cursor."'>\n"
					."<table width='720' cellpadding='0' cellspacing='0' border='0' class='cat_table'>\n"
					.'<tr onmouseover="this.style.backgroundColor = \'#F1F8DD\';" onmouseout="this.style.backgroundColor = \'#ECF3F7\';">'
					."<td width='20px' style='padding-left:".$padding."px'>\n"
					// Pluszeichen Darsellen
					.'<a href="javascript: toggle_visibility(\'p'.$result['id'].'\');" title="'.$MOD_FOLDERGALLERY['EXPAND_COLAPSE'].'">'
					.'<img src="'.THEME_URL.'/images/plus_16.png" onclick="toggle_plus_minus(\''.$result['id'].'\');" name="plus_minus_'.$result['id'].'" border="0" alt="+" />'
					.'</a>'
					// Pluszeichen Ende
					."</td>\n"


					// Zeile Mit allen Angaben
					."<td><a href='".$url['edit'].$result['id']."' title='".$MOD_FOLDERGALLERY['EDIT_CATEGORIE']."'>"
					.'<img src="'.THEME_URL.'/images/visible_16.png" alt="edit" border="0" align="left" style="margin-right: 5px" />'
					.htmlentities($result['categorie'])."</a></td>"
					."<td align='left' width='415'>".htmlentities($result['cat_name'])."</td>"

					//Active:
					.'<td width="30"><img src="'.WB_URL.'/modules/foldergallery/images/active'.$result['active'].'.gif" border="0" alt="" title="active" />&nbsp;&nbsp;</td>'


					// Aktionen Buttons
					."<td width='20'>";
					if ($arrup == true) {$list .="<a href='".WB_URL."/modules/foldergallery/scripts/move_up.php?page_id=".$page_id."&section_id=".$section_id."&id=".$result['id']."' title='".$MOD_FOLDERGALLERY['MOVE_UP']."'>"
					."<img src='".THEME_URL."/images/up_16.png' border='0' alt='v' /></a>";
					}
					$list .= "</td>"
					."<td width='20'>";

					if ($arrdown == true) {$list .="<a href='".WB_URL."/modules/foldergallery/scripts/move_down.php?page_id=".$page_id."&section_id=".$section_id."&id=".$result['id']."' title='".$MOD_FOLDERGALLERY['MOVE_DOWN']."'>"
					."<img src='".THEME_URL."/images/down_16.png' border='0' alt='u' />"
					."</a>";}

					$list .= "</td>";

					/* LÖSCHEN funktioniert ohnehin nicht wirklich, weil die Verzeichnisse beim Synchronisieren wieder auftauchen
					"<td width='20'>"
					."<a href='javascript: confirm_link(\"Sind sie sicher, dass Sie die ausgew&auml;hlte Kategorie mit allen Unterkategorien und Bilder l&ouml;schen m&ouml;chten?\", \"".WB_URL."/modules/foldergallery/scripts/delete_cat.php?page_id=".$page_id."&section_id=".$section_id."&cat_id=".$result['id']."\");' >"
					."<img src='".THEME_URL."/images/delete_16.png' border='0' alt='X'></a>"
					// Ende Zeile mit allen Angaben
					*/


					$list .= "</tr></table>\n"
					."<ul id='p".$result['id']."'style='padding: 1px 0px 1px 0px;' class='cat_subelem'>";
			$list .= display_categories($result['id'], $section_id, $tiefe+1);
			$list .= "</ul></li>\n ";
		} else {
			$list .= "<li id='recordsArray_".$result['id']."' style='padding: 1px 0px 1px 0px;".$cursor."'>\n"
					."<table width='720' cellpadding='0' cellspacing='0' border='0' class='cat_table'>\n"
					.'<tr onmouseover="this.style.backgroundColor = \'#F1F8DD\';" onmouseout="this.style.backgroundColor = \'#ECF3F7\';">'
					."<td width='20px' style='padding-left:".$padding."px'></td>\n"
					// Zeile Mit allen Angaben
					."<td><a href='".$url['edit'].$result['id']."' title='".$MOD_FOLDERGALLERY['EDIT_CATEGORIE']."'>"
					.'<img src="'.THEME_URL.'/images/visible_16.png" alt="edit" border="0" align="left" style="margin-right: 5px" />'
					.htmlentities($result['categorie'])."</a></td>"
					."<td align='left' width='415'>".htmlentities($result['cat_name'])."</td>"

					//Active:
					.'<td width="30"><img src="'.WB_URL.'/modules/foldergallery/images/active'.$result['active'].'.gif" border="0" alt="" title="active" />&nbsp;&nbsp;</td>'
					// Aktionen Buttons
					."<td width='20'>";
					if ($arrup == true) {$list .="<a href='".WB_URL."/modules/foldergallery/scripts/move_up.php?page_id=".$page_id."&section_id=".$section_id."&id=".$result['id']."' title='".$MOD_FOLDERGALLERY['MOVE_UP']."'>"
					."<img src='".THEME_URL."/images/up_16.png' border='0' alt='v' /></a>";
					}
					$list .= "</td>"
					."<td width='20'>";

					if ($arrdown == true) {$list .="<a href='".WB_URL."/modules/foldergallery/scripts/move_down.php?page_id=".$page_id."&section_id=".$section_id."&id=".$result['id']."' title='".$MOD_FOLDERGALLERY['MOVE_DOWN']."'>"
					."<img src='".THEME_URL."/images/down_16.png' border='0' alt='u' />"
					."</a>";}

					$list .= "</td>";
					/* LÖSCHEN funktioniert ohnehin nicht wirklich, weil die Verzeichnisse beim Synchronisieren wieder auftauchen

					."<td width='20'>"
					."<a href='javascript: confirm_link(\"Sind sie sicher, dass Sie die ausgew&auml;hlte Kategorie mit allen Unterkategorien und Bilder l&ouml;schen m&ouml;chten?\", \"".WB_URL."/modules/foldergallery/scripts/delete_cat.php?page_id=".$page_id."&section_id=".$section_id."&cat_id=".$result['id']."\");' >"
					."<img src='".THEME_URL."/images/delete_16.png' border='0' alt='X'></a>"
					// Ende Zeile mit allen Angaben
					*/
					$list .= "</tr></table>\n";
		}
	}
	return $list;
}

?>
