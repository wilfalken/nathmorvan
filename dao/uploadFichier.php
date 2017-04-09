<?php

/* Le \n ne fonctionne pas pour alert().
 */

function uploadFichier() {
    $message = '';
    $idElement = '';
    $nomArticle = '';

    // Récupération du numéro de l'élément modifié
    $idElement = $_POST['idFichier'];
    // Récupération du nom de l'article modifié
    $nomArticle = $_POST['nomArticle'];

    $target_dir = "../donnees/fichiers/";
    // Ne pas oublier la gestion de l'utf8
    $target_file = $target_dir . utf8_decode(basename($_FILES["uploadFichier"]["name"]));
    $uploadOk = 1;
    $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual file or fake file

    if (isset($_POST["submit"])) {
        $check = filesize($_FILES["uploadFichier"]["tmp_name"]);
        if ($check !== false) {
            $message .= "Le fichier est bien valide. ";
            $uploadOk = 1;
        } else {
            $message .= "Le fichier n'est pas valide. ";
            $uploadOk = 0;
        }
    }


    // Check if file already exists
//    if (file_exists($target_file)) {
//        $message .= "Un fichier portant le même nom est déjà présent. Consultez l'article de gestion des fichiers. ";
//        $uploadOk = 0;
//    }
    // Check file size
    if ($_FILES["uploadFichier"]["size"] > 5242880) {
        $message .= "Le fichier est trop lourd. ";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if (strtolower ($fileType) != "pdf" && strtolower ($fileType) != "odt" && strtolower ($fileType) != "odg") {
        $message .= "Seuls les fichiers de types PDF, ODT & ODG sont autorisés. ";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message .= "Le chargement n'est pas possible. ";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["uploadFichier"]["tmp_name"], $target_file)) {
            $message .= "Votre fichier " . basename($_FILES["uploadFichier"]["name"]) . " a bien été chargé. ";
            
            // Renommage du fichier avec un ID
            include '../dao/uploadRenommerFichier.php';
           $nouveauNom = renommerFichier($_FILES["uploadFichier"]["name"], $target_dir);
            rename($target_file, $target_dir . $nouveauNom);
            
            // Mise à jour de la liste des articles
            $_SESSION['articles'][$nomArticle][$idElement][1] = $nouveauNom;
            // Et sauvegarde dans le XML (fonction définie dans articles_dao_write.php
            saveXml($_SESSION['articles'], $_SESSION['barre_menu']);
        } else {
            $message .= "Il y a eu une erreur lors du chargement. ";
        }
    }
    

    unset($_FILES["uploadFichier"]);
    return $message;
}
?>

