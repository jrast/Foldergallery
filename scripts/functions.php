<?PHP
// Direkter Zugriff verhindern
if (!defined('WB_PATH')) die (header('Location: index.php'));


function getSettings($section_id){
	global $database;
	$sql = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_settings WHERE '
		. 'section_id = '.$section_id;
	$query = $database->query($sql);
	$result = $query->fetchRow();
	return $result;
}

/**
 * Generiert ein Thumbnail $thumb aus $file, falls dieses noch nicht vorhanden ist 
 * @return void or true
 * @param string $file  Pfadangabe zum original File
 * @param string $thumb Pfadangabe zum Thumbfile
 ***********************************************
 * Anpassung für individuelle Thumb erstellung
 * @param string $ratio Seitenverhältniss der Thumbs
 * @param string $positionX Position X von jCrop ansonsten 0
 * @param string $positionY Position Y von jCrop ansonsten 0
 * @param string $positionW Position W von jCrop ansonsten 0
 * @param string $positionH Position H von jCrop ansonsten 0
 */
function generateThumb($file, $thumb, $thumb_size, $showmessage, $ratio, $positionX = 0, $positionY = 0, $positionW = 0, $positionH = 0 ){

	//Von Chio eingef�gt:
	global $megapixel_limit;
	if ($megapixel_limit < 2) {$megapixel_limit = 5;}
	
	static $thumbscounter, $thumbsstarttime, $allthumbssizes;
	if (!$thumbscounter) {$thumbscounter = 0;}
	if (!$thumbsstarttime) {$thumbsstarttime = time();}
	if (!$allthumbssizes) {$allthumbssizes = 0;}
	
	if(!is_file($file)) { if ($showmessage == 1) {echo '<b>Missing file:</b> '.$file.'<br/>'; return -1;} }
	
	$thumbscounter++;
	if ($thumbscounter == 80 AND $showmessage == 1) echo('<h3>stopped.. press F5 to reload</h3>');
	if ($thumbscounter > 80) return -1;
	
	$tgone = time() - $thumbsstarttime;	
	if (time() - $thumbsstarttime > 50) die('<h3>timeout.. press F5 to reload</h3>');
	
	//if ($showmessage==1) {echo "<br/>creating thumb: ".$file;}
	// ENDE Chio
	
	
	// Einige Variablen
	$jpg = '\.jpg$|\.jpeg$';
	$gif = '\.gif$';
	$png = '\.png$';
	//$thumb_size = 140;
	$fontSize = 2;
	$bg = "D1F6FF";
	
	$thumbFolder = dirname($thumb);
		

	//Verzeichnis erstellen, falls noch nicht vorhanden
	if(!is_dir($thumbFolder)){
		$u = umask(0);
		if(!@mkdir($thumbFolder, 0777)){
			echo '<!--p style="color: red; text-align: center;">Fehler beim Verzeichniss erstellen</p-->';
		}
		umask($u);
	}
	
	// Thumb erstellen
	if(!is_file($thumb)) {
		//checken, ob megapixel �ber 5:
		if (function_exists('getimagesize')) {
			list($width, $height, $type, $attr) = getimagesize($file);
			$fl = ceil(($width * $height) / 1000000);
			if ($fl > $megapixel_limit){
				if ($showmessage==1) { echo '<br/><b>'.$fl. ' Megapixel; skipped!</b>';}
			 	return -2;
			}
		}
		
		
		if($type == 2) {			
			$original = @imagecreatefromjpeg($file);			
		} elseif ($type == 1) {
			$original = @imagecreatefromgif($file);
		} elseif($type == 3) {
			$original = @imagecreatefrompng($file);
		}
	
		if ( isset($original) ) {		
			if (function_exists('getimagesize')) {
				list($width, $height, $type, $attr) = getimagesize($file);
			} else {
				continue;
			}
			if ($width >= $height && $width > $thumb_size) {
				//#########
				//Thumbnail verarbeitung ver�ndert um einen Ausschnitt der Gr��e der $thumbnail_size
				//zu erhalten um ein gleichm��iges erscheinungsbild im Frontend zu gew�hrleisten
				//by Pumpi
				//#########
				//$smallwidth = $thumb_size;
				//$smallheight = floor($height / ($width / $smallwidth));
				$smallwidth = intval($width*$thumb_size/$height);
				$smallheight = $thumb_size;
				$ofx = 0;
				$ofy = floor(($thumb_size - $thumb_size) / 2);
			} elseif ($width <= $height && $height > $thumb_size) {
				//$smallheight = $thumb_size;
				//$smallwidth = floor($width / ($height / $smallheight));
				$smallheight = intval($height*$thumb_size/$width);
				$smallwidth = $thumb_size;
				$ofx = floor(($thumb_size - $thumb_size) / 2); 
				$ofy = 0;
			} else {
				$smallheight = $height;
				$smallwidth = $width;
				$ofx = floor(($thumb_size - $thumb_size) / 2);
				$ofy = floor(($thumb_size - $thumb_size) / 2);
			}
			
			if (function_exists('imagecreatetruecolor')) {
				if ($height > $thumb_size && $width > $thumb_size) {
					if ($ratio > 1) $small = imagecreatetruecolor($thumb_size, $thumb_size/$ratio);
					else $small = imagecreatetruecolor($thumb_size*$ratio, $thumb_size);
				}
				else {
					$small = imagecreatetruecolor($smallwidth, $smallheight);
				}
			} else {
				$small = imagecreate($smallwidth, $smallheight);
			}
			sscanf($bg, '%2x%2x%2x', $red, $green, $blue);
			$b = imagecolorallocate($small, $red, $green, $blue);
			imagefill($small, 0, 0, $b);
			if ($original) {
				//Änderungen der Variablen die für JCrop Thumberstellung anderst sein müssen
				if (!empty ($positionW) && !empty($positionH)) {
					$width = $positionW;
					$height = $positionH;
					
					//wenn ein Ratio eingestellt ist werden die small Atribute des Thumbs angepasst
					//die ist allerdings nur bei JCrop nötig normal wird die größe vom 0Punkt aus errechnet
					if ($ratio > 1) {
						$smallwidth = $thumb_size;
						$smallheight = $thumb_size / $ratio;
					}
					else {
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
				$htw = ($fw*strlen($filename))/2;
				$hts = $thumb_size/2;
				imagestring($small, $fontSize, $hts-$htw, $hts-($fh/2), $filename, $black);
				imagerectangle($small, $hts-$htw-$fw-1, $hts-$fh, $hts+$htw+$fw-1, $hts+$fh, $black);
			}
			imagejpeg($small, $thumb);
			imagedestroy($original);
			imagedestroy($small);
			return true;
		}
	}
}
?>
