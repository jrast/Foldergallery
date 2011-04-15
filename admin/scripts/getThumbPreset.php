<?php
/**
 * This file is used to comunicate with the LoadPreset-function in the
 * general-settings view.
 *
 * Simply returns a json encoded array with all the settings
 */
require('../../../../config.php');
require(WB_PATH.'/modules/foldergallery/presets/thumbPresets.php');
echo json_encode($thumbPresets[$_GET['preset']]);
?>
