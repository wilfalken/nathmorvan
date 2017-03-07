<?php

echo '<h1>Gestion des images visibles dans le carrousel</h1>';
echo '<br><br>';

// Ajout du formulaire d'upload
$upload = '<span id=upload>';
/* ../dao/uploadImageSlideShow.php */
$upload .= '<form action="" method="post" enctype="multipart/form-data">';
$upload .= "Ajouter une image dans le carrousel : ";
$upload .= ' <input type="file" name="uploadImageSlideShow">';
$upload .= '<input type="submit" value="Charger l\'image dans le carrousel" name="submit">';
$upload .= '</form>';
$upload .= '</span>';
echo $upload;

echo '<br><br>';

// Ajout de la liste des images présentes dans le répertoire du carrousel
$carrousel ='';
$carrousel .= '<span id=slideshow>';
$cssDirectory = "../donnees/images/carrousel";
    if ($dossier = opendir ($cssDirectory)) {
        while ( false !== ($file = readdir ( $dossier )) ) {
            $fileExtension = explode(".", $file)[count (explode(".", $file))-1];
            if ($fileExtension != "") {
                $carrousel .= '<img src="../donnees/images/carrousel/'.$file.'"><br>';
                $carrousel .= '<span class=nomImage>'.$file.'</span>';
                $carrousel .= '<a class=bouton_slideshow name=supprimer_image_carrousel data-nomFichier="'.$file.'" href="">Supprimer cette image du carrousel</a><br><br>';
            }   
        }
        closedir ( $dossier );
    }
$carrousel .='<br><br><br>';

$carrousel .= '</span>';
echo $carrousel;

