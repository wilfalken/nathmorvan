<?php

echo '<h1>Gestion des images stockées (utilisées ou non)</h1>';
echo '<br><br>';


$listeImagesStockees = array();
foreach ($_SESSION['articles'] as $nomArticle => $article) {
    foreach ($article as $element) {
        if ($element[0]=='Image'){
            $listeImagesStockees [$element[1]] = $nomArticle;
        }
    }
}



// Ajout de la liste des images présentes dans le répertoire du carrousel
$affichageListeImages ='';
$affichageListeImages .= '<span id=repertoire>';
$repertoire = "../donnees/images";
    if ($dossier = opendir ($repertoire)) {
        while ( false !== ($file = readdir ( $dossier )) ) {
            $fileExtension = explode(".", $file)[count (explode(".", $file))-1];
            if ($fileExtension != "" && $file != '_image_defaut.jpg' && (strpos($file,"."))) {
                $affichageListeImages .= '<a href="'.$repertoire.'/'.utf8_encode ($file).'" target="_blank"><img src="'.$repertoire.'/'.utf8_encode ($file).'"></a>';
                $affichageListeImages .= '<span class=texte>'.utf8_encode ($file).'<br>(cliquez pour agrandir l\image)</span>';
                if (!array_key_exists(utf8_encode ($file), $listeImagesStockees)){
                    $affichageListeImages .= '<span class=texte>Image non utilisée.</span>';
                    $affichageListeImages .= '<br>';
                    $affichageListeImages .= '<a class=bouton_repertoire name=supprimer_image data-nomFichier="'.utf8_encode ($file).'" data-nomRepertoire="'.$repertoire.'" href="">Supprimer cette image du répertoire</a>';  
                }
                else {
                    $affichageListeImages .= '<span class=texte>Image utilisée dans la page "'.$listeImagesStockees[utf8_encode ($file)].'".</span>';
                }
                $affichageListeImages .= '<br><br><br><br>';
            }
        }
        closedir ( $dossier );
    }
$affichageListeImages .='<br><br><br>';

$affichageListeImages .= '</span>';
echo $affichageListeImages;

