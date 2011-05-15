<?php

/**
 * This class offers a lot of usefull functions to validate forms
 * or other data which have to be validated.
 *
 * @author jrast
 */
class Validator {

    private $_data = array();

    private $_keys = array();

    /**
     * array which contains all validated data
     */
    private $_validated = array();

    /**
     * array with all errors wich occured during
     * multi-validating
     */
    private $_errors = array();

    /**
     * Only the last error
     */
    private $_lastError = '';


    /**
     *
     * @var bool Just a flag to remember if there was a error
     */
    private $_isError = false;

    private $_usePHPFilters = false;

    public function  __construct() {
        if(function_exists('filter_var')) {
            $this->_usePHPFilters = true;
        }
    }


    public function setData(array $data) {
        $this->_data = $data;
    }

    public function setKeys(array  $keys) {
        $this->_keys = $keys;
    }

    public function process() {
        foreach($this->_keys as $key => $type) {
            if(!isset($this->_data[$key])) {
               $this->_errors[$key] = 'Key not set!';
            } else {
                switch($type) {
                    case 'integer' :
                        $this->_validated[$key] = $this->getInteger($this->_data[$key]);
                        break;
                    case 'float'   :
                        $this->_validated[$key] = $this->getFloat($this->_data[$key]);
                        break;
                    case 'boolean' :
                        $this->_validated[$key] = $this->getBoolean($this->_data[$key]);
                        break;
                    case 'numeric' :
                        $this->_validated[$key] = $this->getNumeric($this->_data[$key]);
                        break;
                    case 'string'  :
                        $this->_validated[$key] = $this->getString($this->_data[$key]);
                        break;
                    default :
                        $this->_errors[$key] = 'Could not validate this Type "'.$type.'!';
                }
                if($this->_isError) {
                    $this->_errors[$key] = 'Could not validate '.$key.' as '.$type.'!';
                }
            }
        }

    }

    /**
     *
     * @return array all validated data (also the fields wich didn't pass (these
     * are filled with NULL)
     */
    public function getValidData() {
        return $this->_validated;
    }

    /**
     *
     * @return bool true if there where some errors during proccess()
     */
    public function isError() {
        if(count($this->_errors) > 0) {
            return true;
        } else {
           return false;
        }
    }


    /**
     * This functions checks if the argument is numeric (like the is_numeric of PHP)
     * But it also checks if the argument is not NAN (which is, on my point of view
     * not a number!).
     *
     * @param mixed $value Your variable which will be checked.
     * @return bool true if $value is a Numeric Value
     */
    public function isNumeric($value) {
        if(is_numeric($value)) {
            if(is_nan($value)) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Checks if the argument is an Integer. Uses the isNumeric fucntion to do a
     * precheck.
     *
     * @see Validator::isNumeric
     * @param  mixed $value Your variable which will be checked.
     * @return bool
     */
    public function isInteger($value) {
        if(!$this->isNumeric($value)) {
            return false;
        }
        if(intval($value) == $value) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Checks if the argument is a floating point number. Uses the isNumeric function
     * to do a precheck.
     *
     * @param mixed $value Your variable which will be checked.
     * @return bool
     */
    public function isFloat($value) {
        if(!$this->isNumeric($value)) {
            return false;
        }
        if (floatval($value) == $value) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * The same as isNumeric, but returns the argument if it is numeric
     * @param mixed $value
     * @return mixed the argument if it is numeric, NULL elsewise
     */
    public function getNumeric($value) {
        if(!$this->isNumeric($value)) {
            $this->_isError = true;
            return NULL;
        } else {
            return $value;
        }

    }

    /**
     * Converts the argument to integer if it is possible.
     * @param mixed $value
     * @return int the argument as integer, NULL elsewise
     */
    public function getInteger($value) {
        if($this->_usePHPFilters) {
            $t = filter_var($value, FILTER_VALIDATE_INT);
            if($t === false) {
                $this->_isError = true;
                return NULL;
            } else {
                return $t;
            }
        }
        if(!$this->isInteger($value)) {
            $this->_isError = true;
            return false;
        } else {
            return intval($value);
        }
    }

    /**
     * Converts the argument to float if possible
     * @param mixed $value
     * @return float the argument as float, NULL elsewise
     */
    public function getFloat($value) {
        if($this->_usePHPFilters) {
            $t = filter_var($value, FILTER_VALIDATE_FLOAT);
            if($t === false) {
                $this->_isError = true;
                return NULL;
            } else {
                return $t;
            }
        }
        if(!$this->isFloat($value)) {
            $this->_isError = true;
            return false;
        } else {
            return floatval($value);
        }
    }

    /**
     * Checks if a given value is a boolean similar value
     * Values which are treated as boolean are:
     * (bool) true, (bool) false, 1, 0, yes, no, true, false, on, off
     * Upper/Lowercase is ignored, so Yes or oFF would also return true!
     *
     * @param mixed $value
     * @return bool true if it is a booleanlike value
     */
    public function isBoolean($value) {
        if($this->_usePHPFilters) {
            $t = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if($t === NULL) {
                $this->_isError = true;
                return NULL;
            } else {
                return $t;
            }
        }
        if(is_bool($value)) {
            return true;
        }
        $len = strlen($value);
        $value = strtolower($value);
        switch ($len) {
            case 1: if($value == 1 || $value == 0) return true;
                break;
            case 2: if($value == 'no' || $value == 'on' ) return true;
                break;
            case 3: if($value == 'yes' || $value == 'off') return true;
                break;
            case 4: if($value == 'true') return true;
            case 5: if($value == 'false') return true;
            default: return false;
        }
        return false;
    }

    /**
     * Returns the boolean value of the parameter
     * See isBoolean for more detailes!
     * @see Validator::isBoolean
     * @param mixed $value
     * @return bool true/false
     */
    public function getBoolean($value) {
        if($this->_usePHPFilters) {
            $t = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if($t === NULL) {
                $this->_isError = true;
                return NULL;
            } else {
                return $t;
            }
        }
        if($this->isBoolean($value)) {
            if(is_bool($value)) {
                return (bool) $value;
            }
            $len = strlen($value);
            $value = strtolower($value);
            switch ($len) {
                case 1: if($value == 1) {
                            return true;
                        } elseif($value == 0) {
                            return false;
                        }
                    break;
                case 2: if($value == 'no') {
                            return false;
                        } elseif($value == 'on' ) {
                            return true;
                        }
                    break;
                case 3: if($value == 'yes') {
                            return true;
                        } elseif($value == 'off') {
                            return false;
                        }
                    break;
                case 4: if($value == 'true') return true;
                case 5: if($value == 'false') return false;
                default: $this->_isError = true;
                         return NULL;
            }
        }
        $this->_isError = true;
        return NULL;
    }


    public function getString($value) {
        return trim($value);
    }


    public function getRegexMatch($value, $regex) {
        if($this->_usePHPFilters) {
            $options = array(
                'options' => array('regexp' => $regex)
            );
            $t = filter_var($value, FILTER_VALIDATE_REGEXP, $options);
            if($t === false) {
                return '';
            } else {
                return $t;
            }
        }
        if (is_string($value)) {
            if (!preg_match($regex, $value)) {
                return '';
            } else {
                return $value;
            }
        } else {
            return '';
        }
    }
}

?>

