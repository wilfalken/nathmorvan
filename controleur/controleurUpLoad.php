<?php
// Include de articles_dao_write.php pour sauvegarder le nom du fichier dans le xml
include '../dao/articles_dao_write.php';        
if (!empty ($_FILES["uploadImageSlideShow"])){
    include '../dao/uploadImageSlideShow.php';
    $message = uploadImageSlideShow();
}
if (!empty ($_FILES["uploadFichier"])){
    include '../dao/uploadFichier.php';
    $message = uploadFichier();
}
if (!empty ($_FILES["uploadImage"])){
    include '../dao/uploadImage.php';
    $message = uploadImage();
}

