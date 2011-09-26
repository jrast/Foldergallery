<?php
/**
 * Description of DirectoryHanlder
 *
 * @author jrast
 */
class DirectoryHandler {
    const OS_WIN = 'Windows Server';
    const OS_UNIX = 'Unix Server';
    static $OS = null;

    private static function detectOS() {
        if(self::$OS == null) {
            if(stristr(PHP_OS, 'WIN')) {
                self::$OS = self::OS_WIN;
            }
            else {
                self::$OS = self::OS_UNIX;
            }
        }
        return self::$OS;
    }

    public static function DecodePath($path) {
        if(self::detectOS() == self::OS_WIN)
            return utf8_decode($path);
        return $path;
    }

    public static function EncodePath($path) {
        if(self::detectOS() == self::OS_WIN)
            return utf8_encode($path);
        return $path;
    }
}

?>
