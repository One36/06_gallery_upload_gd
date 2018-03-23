<?php

function calcDimension($x1, $y1, $x2) {

    return $y1 * $x2 / $x1;
}

function getRandName($prefix = '') {
    return str_replace('.', '_', uniqid($prefix, true));
}

// Quellbild wird als Objekt zurückgegeben
function getGdImage($path) {

    $info = getimagesize($path);
    $img = false;
    switch ($info[2]) {
        case 1:
            $img = imagecreatefromgif($path);
            break;
        case 2:
            $img = imagecreatefromjpeg($path);
            break;
        case 3:
            $img = imagecreatefrompng($path);
            break;
        default:
            $img = false;
            break;
    }
    return [$img, $info[0], $info[1], $info[2]];
}

function createResample($srcImg, $srcWidth, $srcHeight, $dstWidth, $dstHeight, $filetype, $path, $filename, $compression = DEFAULT_COMPRESSION_LEVEL) {
    $dstPath = false;
    $dstImg = imagecreatetruecolor($dstWidth, $dstHeight);
    imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $dstWidth, $dstHeight, $srcWidth, $srcHeight);

    if ($filetype === 2) {

        $dstPath = $path . $filename . '.jpeg';
        imagejpeg($dstImg, $dstPath, $compression);
    } elseif ($filetype === 3) {
        $dstPath = $path . $filename . '.png';
        imagepng($dstImg, $dstPath, $compression);
    } else {
        return false;
    }
    return $dstPath;
}

function getImageFileType($path) {
    $types = ['', 'gif', 'jpeg', 'png'];
    $type = getimagesize($path)[2];
    if ($type > 0 && $type < 4) {
        return $types[$type];
    }
    return false;
}

function uploadFile($tmpName, $path, $dstName = false) {
    $n = ($dstName) ? $dstName : getRandName() . '.' . getImageFileType($tmpName);
    if (move_uploaded_file($tmpName, $path . $n)) {
        return $path . $n;
    }
    return false;
}

function uploadFiles($files, $path) {
    $uploaded = [];
    for ($i = 0; $i < count($_FILES['uplImgs']['tmp_name']); $i++) {
        $uploaded[] = uploadFile($files['tmp_name'][$i], $path);
    }
    return $uploaded;
}

function createThumbnails($files, $retina = [1]) {

    for ($i = 0; $i < count($files); $i++) {
        //gdImg[0] - Bilddaten
        //gdImg[1] - Breite
        //gdImg[2] - Höhe
        //gdImg[3] - Typ
        $gdImg = getGdImage($files[$i]);

        // THUMBNAILS
        $dstH = intval(calcDimension($gdImg[1], $gdImg[2], THUMBS_WIDTH));
        foreach ($retina as $value) {
            $name = pathinfo($files[$i])['filename'] . '_' . THUMBS_WIDTH . 'x' . $dstH . '@' . $value . 'x';
            createResample($gdImg[0], $gdImg[1], $gdImg[2], THUMBS_WIDTH * $value, $dstH * $value, IMAGETYPE_JPEG, PATH_THUMBNAILS, $name);
        }

        // FULLSIZE -> define('THUMBS_FULLSIZE', 1000); > config.php
        $dstH = intval(calcDimension($gdImg[1], $gdImg[2], THUMBS_FULLSIZE));
        foreach ($retina as $value) {
            $name = pathinfo($files[$i])['filename'] . '_' . THUMBS_FULLSIZE . 'x' . $dstH . '@' . $value . 'x';
            createResample($gdImg[0], $gdImg[1], $gdImg[2], THUMBS_FULLSIZE * $value, $dstH * $value, IMAGETYPE_JPEG, PATH_THUMBNAILS, $name);
        }
    }
}