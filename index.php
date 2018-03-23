<?php
require_once './config.php';
require_once './includes/functions.php';


if (isset($_FILES['uplImgs'])) {
    $uploadedFiles = uploadFiles($_FILES['uplImgs'], PATH_ORIGINALS);
   
    createThumbnails($uploadedFiles,[1,2,3,4]);
}

?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PHP 06 GALLERY</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="assets/css/styles.css">    
        <script src="assets/js/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/js/main.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="container">
            <form method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>Dateien hochladen</legend>
                </fieldset>
                <input class="form-control-file" type="file" multiple name="uplImgs[]">
                <hr>
                <fieldset>
                    <button class="btn btn-primary">Upload</button>
                </fieldset>

            </form>
        </div>
        <pre>

        </pre>
    </body>
</html>
