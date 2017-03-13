<?php

echo '<h1>Gestion des images visibles dans le carrousel</h1>';
echo '<br><br>';

// Ajout du formulaire d'upload
$upload = '<span id=upload>';
/* "action" est vide, il s'agit d'un lien.
 * Ici, on le garde vide, puisqu'on cherche à rester sur la page.
 * Les données mises dans $_FILES et $_POST sont récupérées par l'index.
 */
$upload .= '<form action="" method="post" enctype="multipart/form-data">';
$upload .= "Ajouter une image dans le carrousel : ";
$upload .= ' <input type="file" name="uploadImageSlideShow">';
$upload .= '<input type="submit" value="Charger l\'image dans le carrousel" name="submit">';
$upload .= '</form>';
$upload .= '</span>';
echo $upload;

echo '<br><br>';

// Ajout de la liste des images présentes dans le répertoire du carrousel
$affichageListeCarrousel ='';
$affichageListeCarrousel .= '<span id=repertoire>';
$repertoire = "../donnees/images/carrousel";
    if ($dossier = opendir ($repertoire)) {
        while ( false !== ($file = readdir ( $dossier )) ) {
            $fileExtension = explode(".", $file)[count (explode(".", $file))-1];
            if ($fileExtension != "") {
                $affichageListeCarrousel .= '<a href="'.$repertoire.'/'.$file.'" target="_blank"><img src="'.$repertoire.'/'.$file.'"></a><br>';
                $affichageListeCarrousel .= '<span class=texte>'.$file.'<br>(cliquez pour agrandir l\image)</span>';
                $affichageListeCarrousel .= '<a class=bouton_repertoire name=supprimer_image_carrousel data-nomFichier="'.$file.'" data-nomRepertoire="'.$repertoire.'" href="">Supprimer cette image du carrousel</a><br><br>';
                $affichageListeCarrousel .='<br><br><br>';
            }   
        }
        closedir ( $dossier );
    }


$affichageListeCarrousel .= '</span>';
echo $affichageListeCarrousel;

