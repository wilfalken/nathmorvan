<?php

/* Le \n ne fonctionne pas pour arlert().
 * Il faut mettre \\\n.
 */

function uploadImage() {
    $message = '';
    $idElement = '';
    $nomArticle = '';

    // Récupération du numéro de l'élément modifié
    $idElement = $_POST['idImage'];
    // Récupération du nom de l'article modifié
    $nomArticle = $_POST['nomArticle'];

    $target_dir = "../donnees/images/";
    // Ne pas oublier la gestion de l'utf8
    $target_file = $target_dir . utf8_decode(basename($_FILES["uploadImage"]["name"]));
    $uploadOk = 1;
    $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["uploadImage"]["tmp_name"]);
        if ($check !== false) {
            $message .= "Le fichier est bien une image - " . $check["mime"] . ". ";
            $uploadOk = 1;
        } else {
            $message .= "Le fichier n'est pas une image. ";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
//    if (file_exists($target_file)) {
//        $message .= "Une image portant le même nom est déjà présente. Consultez l'article de gestion des images. ";
//        $uploadOk = 0;
//    }
    // Check file size
    if ($_FILES["uploadImage"]["size"] > 5242880) {
        $message .= "L'image est trop lourde. ";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if (strtolower($fileType) != "jpg" && strtolower($fileType) != "png" && strtolower($fileType) != "jpeg" && strtolower($fileType) != "gif") {
        $message .= "Seules les images de types JPG, JPEG, PNG & GIF sont autorisées. ";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message .= "Le chargement n'est pas possible. ";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["uploadImage"]["tmp_name"], $target_file)) {
            $message .= "Votre fichier " . basename($_FILES["uploadImage"]["name"]) . " a bien été chargé. ";
            // Renommage du fichier avec un ID
            include '../dao/uploadRenommerFichier.php';
            $nouveauNom = renommerFichier(utf8_decode($_FILES["uploadImage"]["name"]), $target_dir);
            rename($target_file, $target_dir . $nouveauNom);

            // Mise à jour de la liste des articles
            $_SESSION['articles'][$nomArticle][$idElement][1] = utf8_encode($nouveauNom);
            // Et sauvegarde dans le XML (fonction définie dans articles_dao_write.php
            saveXml($_SESSION['articles'], $_SESSION['barre_menu']);


            // Copie du fichier dans le répertoire des vignettes
            copy($target_dir . $nouveauNom, $target_dir . "vignettes/" . $nouveauNom);
            // Mise à la taille définie dans donnees/config.php
            include '../donnees/config.php';
            
            // Création d'un objet de type image où sont les pixels originaux
            switch (strtolower($fileType)) {
                case "jpg":
                case "jpeg":
                    $imageOrigine = imagecreatefromjpeg($target_dir . "vignettes/" . $nouveauNom);
                    break;
                case "png":
                    $imageOrigine = imagecreatefrompng($target_dir . "vignettes/" . $nouveauNom);
                    break;
                case "gif":
                    $imageOrigine = imagecreatefromgif($target_dir . "vignettes/" . $nouveauNom);
                    break;
            }
            
             
            // Récupération des dimensions de l'image
            $dimensionsActuellesImage = getimagesize($target_dir . "vignettes/" . $nouveauNom);
            
            // Calcul de la réduction
            if ($dimensionsActuellesImage[0] / $dimensionsImagesReduites[0] < $dimensionsActuellesImage[1] / $dimensionsImagesReduites[1]) {
                $nouvelleLargeur = $dimensionsActuellesImage[0] / $dimensionsActuellesImage[1] * $dimensionsImagesReduites[1];
                $nouvelleHauteur = $dimensionsImagesReduites[1];
            } else {
                $nouvelleLargeur = $dimensionsImagesReduites[0];
                $nouvelleHauteur = $dimensionsActuellesImage[1] / $dimensionsActuellesImage[0] * $dimensionsImagesReduites[0];
            }
            
            // Création d'un deuxième objet de type image qui va recevoir les pixels "réduits"
            $vignette = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur) or die("Erreur");
            
            // Réduction de l'image
            imagecopyresampled($vignette, $imageOrigine, 0, 0, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $dimensionsActuellesImage[0], $dimensionsActuellesImage[1]);

            // Sauvegarde de la vignette à un taux de compression élévé (=nombre faible)
            switch (strtolower($fileType)) {
                case "jpg":
                case "jpeg":
                    imagejpeg($vignette, $target_dir . "vignettes/" . $nouveauNom, 40);
                    break;
                case "png":
                    imagepng($vignette, $target_dir . "vignettes/" . $nouveauNom, 4);
                    break;
                case "gif":
                    imagegif($vignette, $target_dir . "vignettes/" . $nouveauNom);
                    break;
            }
            
            // Suppression des deux objets
            imagedestroy($imageOrigine);
            imagedestroy($vignette);
            
            
            
            
        } else {
            $message .= "Il y a eu une erreur lors du chargement. ";
        }
    }


    unset($_FILES["uploadImage"]);
    return $message;
}
?>

