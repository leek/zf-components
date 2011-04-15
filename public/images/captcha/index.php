<?php

define('SALT', 'CHANGE_ME');

// Parse request
$request = isset($_GET['r']) ? $_GET['r'] : exit;
$request = base64_decode(urldecode($request));
parse_str($request, $request);

// Check request
$categoryHash = isset($request['c'])  ? $request['c'] : exit;
$filename     = isset($request['f'])  ? $request['f'] : exit;
$random       = isset($request['id']) ? $request['id'] : exit;

// Determine category
$categories = glob("./*", GLOB_ONLYDIR);
foreach ($categories as $category) {
    $category = substr($category, strrpos($category, '/') + 1);
    $hash     = md5(SALT . ":$random:$category");
    
    if ($hash == $categoryHash) {
        
        // Match found, output image
        $filepath = "./$category/$filename.jpg";
        $image    = file_get_contents($filepath);

        header("Content-Type: image/jpeg");
        header("Content-Length: " . strlen($image));
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: no-cache");
        echo $image;
        exit;
        
    }
}