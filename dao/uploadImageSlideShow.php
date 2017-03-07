<?php


if (!empty ($_FILES["uploadImageSlideShow"])){

    $target_dir = "../donnees/images/carrousel/";
    // Ne pas oublier la gestion de l'utf8
    $target_file = $target_dir . utf8_decode (basename($_FILES["uploadImageSlideShow"]["name"]));
    $uploadOk = 1;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["uploadImageSlideShow"]["tmp_name"]);
        if($check !== false) {
            echo "Le fichier est bien une image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "Le fichier n'est pas une image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Désolé, une image portant le même nom est déjà présente.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["uploadImageSlideShow"]["size"] > 5000000) {
        echo "Désolé, votre image est trop lourde.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
    && $fileType != "gif" ) {
        echo "Désolé, seules les images de types JPG, JPEG, PNG & GIF sont autorisées.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Désolé, le chargement n'est pas possible.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["uploadImageSlideShow"]["tmp_name"], $target_file)) {
            echo "Votre fichier ". basename( $_FILES["uploadImageSlideShow"]["name"]). " a bien été chargé.";
        } else {
            echo "Désolé, il y a eu une erreur lors du chargement.";
        }
    }
}
?>

