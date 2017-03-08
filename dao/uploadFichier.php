<?php

function uploadFichier(){
    $message = '';
    $idElement = '';
    $nomArticle = '';
    if (!empty ($_FILES["uploadFichier"])){
        // Récupération du numéro de l'élément modifié
        $idElement = $_POST['idFichier'];
        // Récupération du nom de l'article modifié
        $nomArticle = $_POST['nomArticle'];
        
        $target_dir = "../donnees/fichiers/";
        // Ne pas oublier la gestion de l'utf8
        $target_file = $target_dir . utf8_decode (basename($_FILES["uploadFichier"]["name"]));
        $uploadOk = 1;
        $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual file or fake file

        if(isset($_POST["submit"])) {
            $check = filesize($_FILES["uploadFichier"]["tmp_name"]);
            if($check !== false) {
                $message .= "Le fichier est bien valide - " . $check["mime"] . ".<br>";
                $uploadOk = 1;
            } else {
                $message .= "Le fichier n'est pas valide.\n";
                $uploadOk = 0;
            }
        }


        // Check if file already exists
        if (file_exists($target_file)) {
            $message .= "Un fichier portant le même nom est déjà présent.<br>";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["uploadFichier"]["size"] > 5000000) {
            $message .= "Le fichier est trop lourd.<br>";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($fileType != "pdf" && $fileType != "odt" && $fileType != "odg" ) {
            $message .= "Seules les fichiers de types PDF, ODT & ODG sont autorisées.<br>";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $message .= "Le chargement n'est pas possible.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["uploadFichier"]["tmp_name"], $target_file)) {
                $message .= "Votre fichier ". basename( $_FILES["uploadFichier"]["name"]). " a bien été chargé.";
                // Mise à jour de la liste des articles
                $_SESSION['articles'][$nomArticle][$idElement][1]=basename( $_FILES["uploadImage"]["name"]);
                // Et sauvegarde dans le XML (fonction définie dans articles_dao_write.php
                saveXml ($_SESSION['articles'], $_SESSION['barre_menu']);
            } else {
                $message .= "Il y a eu une erreur lors du chargement.";
            }
        }
    }
    unset($_FILES["uploadFichier"]);
    return $message.'<br>'.$idElement.'<br>'.$nomArticle;
}
?>
