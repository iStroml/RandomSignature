<?php
$folder = '.';
    $extList = array();
    $extList['png'] = 'image/png';
    $extList['jpg'] = 'image/jpeg';
    $extList['gif'] = 'image/gif';
    $extList['jpeg'] = 'image/jpeg';

$img = null;

if (substr($folder,-1) != '/') {
    $folder = $folder.'/';
}

if (isset($_GET['img'])) {
    $pathinfo = pathinfo($_GET['img']);
    if (
        isset( $extList[ strtolower( $pathinfo['extension'] ) ] ) &&
        file_exists( $folder.$pathinfo['basename'] )
    ) {
        $img = $folder.$pathinfo['basename'];
    }
} else {
    $fileList = array();
    $handle = opendir($folder);
    while ( false !== ( $file = readdir($handle) ) ) {
        $file_info = pathinfo($file);
        if (
            isset( $extList[ strtolower( $file_info['extension'] ) ] )
        ) {
            $fileList[] = $file;
        }
    }
    closedir($handle);

    if (count($fileList) > 0) {
        $imageNumber = time() % count($fileList);
        $img = $folder.$fileList[$imageNumber];
    }
}

if ($img!=null) {
    $pathinfo = pathinfo($img);
    $contentType = 'Content-type: '.$extList[ $pathinfo['extension'] ];
    header ($contentType);
    readfile($img);
} else {
    if ( function_exists('imagecreate') ) {
        header ("Content-type: image/png");
        $im = @imagecreate (100, 100)
            or die ("Cannot initialize new image stream");
        $background_color = imagecolorallocate ($im, 255, 255, 255);
        $text_color = imagecolorallocate ($im, 0,0,0);
        imagestring ($im, 2, 5, 5,  "ERROR", $text_color);
        imagepng ($im);
        imagedestroy($im);
    }
}

?>


/*
   The script displays a random image with the extension of $extList in the specified folder configued in $folder
   $folder = the configured path of the images
   $extList = Array containting all img extensions

*/
