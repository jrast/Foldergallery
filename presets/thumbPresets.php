<?php
/*
 * Here you can create your own presets!
 * Just look at the standard presets to create your own!
 */
$thumbPresets = array(
    '1:1Croped150'      => array(
        'description'   => 'Ratio: 1:1, Cropped, 150px',
        'image_resize'  => true,
        'image_x'       => 150,
        'image_y'       => 150
    ),
    '3:4Cropped150'     => array(
        'description'   => 'Ratio: 3:4, Cropped, width: 150px',
        'image_resize'  => true,
        'image_x'       => 150,
        'image_y'       => 112
    ),
    '4:3Cropped150'     => array(
        'description'   => 'Ratio: 4:3, Cropped, heigth: 150px',
        'image_resize'  => true,
        'image_x'       => 112,
        'image_y'       => 150
    ),
    '16:9Cropped150'    => array(
        'description'   => 'Ratio: 16:9, Cropped, width: 150px',
        'image_resize'  => true,
        'image_x'       => 150,
        'image_y'       => 84
    ),
    '9:16Cropped150'    => array(
        'description'   => 'Ratio: 9:16, Cropped, height: 150px',
        'image_resize'  => true,
        'image_x'       => 84,
        'image_y'       => 150
    ),
    '1:1noCrop105'      => array(
        'description'   => 'Ratio: 1:1, 150px',
        'image_resize'  => true,
        'image_x'       => 150,
        'image_y'       => 150,
        'image_ratio'   => true
    )
);
?>
    