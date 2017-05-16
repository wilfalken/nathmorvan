<?php

/* Le \n ne fonctionne pas pour alert().
 * Il faut mettre \\\n.
 */

function uploadImageSlideShow() {
    $message = '';


    $target_dir = "../donnees/images/carrousel/";
    // Ne pas oublier la gestion de l'utf8
    $target_file = $target_dir . utf8_decode(basename($_FILES["uploadImageSlideShow"]["name"]));
    $uploadOk = 1;
    $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["uploadImageSlideShow"]["tmp_name"]);
        if ($check !== false) {
            $message .= "Le fichier est bien une image - " . $check["mime"] . ". ";
            $uploadOk = 1;
        } else {
            $message .= "Le fichier n'est pas une image. ";
            $uploadOk = 0;
        }
    }
//     Check if file already exists
//    if (file_exists($target_file)) {
//        $message .= "Une image portant le même nom est déjà présente. ";
//        $uploadOk = 0;
//    }
    // Check file size
    if ($_FILES["uploadImageSlideShow"]["size"] > 5242880) {
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
        if (move_uploaded_file($_FILES["uploadImageSlideShow"]["tmp_name"], $target_file)) {
            $message .= "Votre fichier " . basename($_FILES["uploadImageSlideShow"]["name"]) . " a bien été chargé. ";

            // Renommage du fichier avec un ID
            include '../dao/uploadRenommerFichier.php';
            $nouveauNom = renommerFichier(utf8_decode($_FILES["uploadImageSlideShow"]["name"]), $target_dir);
            rename($target_file, $target_dir . $nouveauNom);
            // Copie du fichier dans le répertoire des vignettes
            copy($target_dir . $nouveauNom, $target_dir . "vignettes/" . $nouveauNom);
            // Mise à la taille définie dans donnees/config.php
            include '../donnees/config.php';


            // Création des deux objets de type image
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
            $vignette = imagecreatetruecolor($dimensionsImagesReduitesSlideshow[0], $dimensionsImagesReduitesSlideshow[1]) or die("Erreur");

            // Récupération des dimensions de l'image
            $dimensionsActuellesImage = getimagesize($target_dir . "vignettes/" . $nouveauNom);

            // Calcul de la zone de pixels qui va être utilisée dans la vignette
            if ($dimensionsActuellesImage[0] / $dimensionsImagesReduitesSlideshow[0] > $dimensionsActuellesImage[1] / $dimensionsImagesReduitesSlideshow[1]) {
                $nouvelleLargeur = $dimensionsImagesReduitesSlideshow[0] / $dimensionsImagesReduitesSlideshow[1] * $dimensionsActuellesImage[1];
                $nouvelleHauteur = $dimensionsActuellesImage[1];
            } else {
                $nouvelleLargeur = $dimensionsActuellesImage[0];
                $nouvelleHauteur = $dimensionsImagesReduitesSlideshow[1] / $dimensionsImagesReduitesSlideshow[0] * $dimensionsActuellesImage[0];
            }

            // Calcul de la position du coin supérieur gauche de la zone de pixels choisie
            $decalageLargeur = -($nouvelleLargeur - $dimensionsActuellesImage[0]) / 2;
            $decalageHauteur = -($nouvelleHauteur - $dimensionsActuellesImage[1]) / 2;

            // Copie de la zone de pixels choisie dans l'image d'origine vers la vignette
            imagecopyresampled($vignette, $imageOrigine, 0, 0, $decalageLargeur, $decalageHauteur, $dimensionsImagesReduitesSlideshow[0], $dimensionsImagesReduitesSlideshow[1], $nouvelleLargeur, $nouvelleHauteur);

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


    unset($_FILES["uploadImageSlideShow"]);
    return $message;
}
?>

