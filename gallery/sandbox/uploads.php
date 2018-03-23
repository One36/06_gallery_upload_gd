<?php
require_once '../config.php';
require_once '../includes/functions.php';

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
    move_uploaded_file($tmpName, $path . $n);
}

if (isset($_FILES['uplImgs'])) {
    for ($i = 0; $i < count($_FILES['uplImgs']['tmp_name']); $i++) {
        $tmpName = $_FILES['uplImgs']['tmp_name'][$i];
        uploadFile($tmpName, '../' . PATH_ORIGINALS);
    }
}
?>


<html>

    <form method="post" enctype="multipart/form-data">
        <input multiple type="file"  name="uplImgs[]">
        <button>send</button>
    </form>

</html>












<?php

//if (isset($_FILES['uploadFiles'])) {
//    $imageTypes = ['', '.gif', '.jpeg', '.png'];
//    $amountImages = count($_FILES['uploadFiles']['name']);
//    for ($i = 0; $i < $amountImages; $i++) {
//        $src = $_FILES['uploadFiles']['tmp_name'][$i];
//        $imgInfo = getimagesize($src);
//        $imgType = $imgInfo[2]; // 1 (gif) 2 (jpeg)  3 (png)
//        if ($imgType >= 1 && $imgType <= 3) {
//
//            $folder = './uploads/';
//            $filename = uniqid('634287', true);
//            $filetype = $imageTypes[$imgType];
//
//            $dst = $folder . $filename . $filetype;
//            move_uploaded_file($src, $dst);
//        }
//    }
//    header("Location:files_multiple.php");
//}