<?php

function uploadImage(){
    $message = '';
    $idElement = '';
    $nomArticle = '';
    if (!empty ($_FILES["uploadImage"])){
        // Récupération du numéro de l'élément modifié
        $idElement = $_POST['idImage'];
        // Récupération du nom de l'article modifié
        $nomArticle = $_POST['nomArticle'];
        
        $target_dir = "../donnees/images/";
        // Ne pas oublier la gestion de l'utf8
        $target_file = $target_dir . utf8_decode (basename($_FILES["uploadImage"]["name"]));
        $uploadOk = 1;
        $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["uploadImage"]["tmp_name"]);
            if($check !== false) {
                $message .= "Le fichier est bien une image - " . $check["mime"] . ".<br>";
                $uploadOk = 1;
            } else {
                $message .= "Le fichier n'est pas une image.<br>";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $message .= "Une image portant le même nom est déjà présente.<br>";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["uploadImage"]["size"] > 5000000) {
            $message .= "L'image est trop lourde.<br>";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
        && $fileType != "gif" ) {
            $message .= "Seules les images de types JPG, JPEG, PNG & GIF sont autorisées.<br>";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $message .= "Le chargement n'est pas possible.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["uploadImage"]["tmp_name"], $target_file)) {
                $message .= "Votre fichier ".basename( $_FILES["uploadImage"]["name"])." a bien été chargé.";
                // Mise à jour de la liste des articles
                $_SESSION['articles'][$nomArticle][$idElement][1]=basename( $_FILES["uploadImage"]["name"]);
                // Et sauvegarde dans le XML (fonction définie dans articles_dao_write.php
                saveXml ($_SESSION['articles'], $_SESSION['barre_menu']);
            } else {
                $message .= "Il y a eu une erreur lors du chargement.";
            }
        }
    }
    unset($_FILES["uploadImage"]);
    return $message;
}
?>

