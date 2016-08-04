<?php

// Image type PNG
header('Content-Type: image/png');

$width          = 256;
$height         = 256;
$max_zoom       = 3;
$min_zoom       = .1;
$normal_zoom    = 1;

$zoom = isset($_GET['zoom']) ? (($_GET['zoom'] < $min_zoom || $_GET['zoom'] > $max_zoom) ? $normal_zoom : $_GET['zoom']) : $normal_zoom;

if(!isset($_GET['base'])) $_GET['base'] = 'ff8800';
if(!isset($_GET['secondary'])) $_GET['secondary'] = 'f8f8f8';

list($r['base'], $g['base'], $b['base']) = sscanf($_GET['base'], "%02x%02x%02x");
list($r['secondary'], $g['secondary'], $b['secondary']) = sscanf($_GET['secondary'], "%02x%02x%02x");

$assets = [
    'base'      => [
        'url' => 'img/items/candy/candy_painted_base_color.png',
        'color' => true,
    ],
    'secondary'      => [
        'url' => 'img/items/candy/candy_painted_secondary_color.png',
        'color' => true,
    ],
    'highlight'      => [
        'url' => 'img/items/candy/candy_painted_highlight.png',
        'color' => false,
    ],
];

$bg = imagecreatetruecolor($width * $zoom, $height * $zoom);

imagesavealpha($bg, true);
imagealphablending($bg, true);

$transparent = imagecolorallocatealpha($bg, 0, 0, 0, 127);
imagefill($bg, 0, 0, $transparent);



foreach($assets as $key => $url){
    $image = imagecreatefrompng($url['url']);

    imagealphablending($image, false);
    imagesavealpha($image, true);
    
    if($url['color']){
        imagefilter($image, IMG_FILTER_NEGATE); 
        imagefilter($image, IMG_FILTER_COLORIZE, 255 - $r[$key], 255 - $g[$key], 255 - $b[$key]); 
        imagefilter($image, IMG_FILTER_NEGATE); 
    }
    
    imagecopyresampled($bg, $image, 0, 0, 0, 0, $width * $zoom, $height * $zoom, $width, $height);
    
    imagedestroy($image);
    
}


imagepng($bg);

imagedestroy($bg);