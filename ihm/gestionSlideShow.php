<?php


echo '<h1>Gestion des images<br>visibles dans le carrousel</h1>';
echo '<br><br>';


echo '<p style="text-align: center;">Ajouter une image dans le carrousel :</p>';
//echo '<p style="text-align: center;" id="affichageFichierChoisi">Aucun fichier sélectionné</p>';
// Ajout du formulaire d'upload
//$upload = '<span id=upload>';

/* "action" est vide, il s'agit d'un lien.
 * Ici, on le garde vide, puisqu'on cherche à rester sur la page.
 * Les données mises dans $_FILES et $_POST sont récupérées par l'index.
 */
$upload = '<form class="upload" action="" method="post" enctype="multipart/form-data">';
$upload .= '<input type="file" name="uploadImageSlideShow" id="file" class="inputFile">';
$upload .= '<label for="file" class="labelInput">Choisir une image ...</label>';
$upload .= '<label for=" " id="affichageFichierChoisi" class="textInput">Aucune image sélectionnée</label>';
$upload .= '<br><br>';
$upload .= '<input type="submit" id="modifierFichierChoisi" value="Charger l\'image dans le carrousel" name="submit"  disabled>';
$upload .= '</form>';
//$upload .= '</span>';
echo $upload;

echo '<br><br>';
echo '<p style="text-align: center;">Images déjà présentes dans le carrousel :</p><br>';
// Ajout de la liste des images présentes dans le répertoire du carrousel
$affichageListeCarrousel ='';
$affichageListeCarrousel .= '<span id=repertoire>';
$repertoire = "../donnees/images/carrousel";
    if ($dossier = opendir ($repertoire)) {
        while ( false !== ($file = readdir ( $dossier )) ) {
            $fileExtension = explode(".", $file)[count (explode(".", $file))-1];
            if ($fileExtension != "" && (strpos($file,"."))) {
                $affichageListeCarrousel .= '<a href="'.$repertoire.'/'.utf8_encode($file).'" target="_blank"><img src="'.$repertoire.'/vignettes/'.utf8_encode($file).'"></a><br>';
                $affichageListeCarrousel .= '<span class=texte>'.utf8_encode($file).'<br>(cliquez pour agrandir l\'image)</span>';
                $affichageListeCarrousel .= '<a class=bouton_repertoire name=supprimer_image_carrousel data-nomFichier="'.utf8_encode($file).'" data-nomRepertoire="'.$repertoire.'" href="">Supprimer cette image du carrousel</a><br><br>';
                $affichageListeCarrousel .='<br><br><br>';
            }   
        }
        closedir ( $dossier );
    }


$affichageListeCarrousel .= '</span>';
echo $affichageListeCarrousel;

