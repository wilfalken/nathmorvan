<?php

echo '<h1>Gestion des fichiers stockés (utilisés ou non)</h1>';
echo '<br><br>';


$listeFichiersStockes = array();
foreach ($_SESSION['articles'] as $nomArticle => $article) {
    foreach ($article as $element) {
        if ($element[0]=='Fichier'){
            $listeFichiersStockes [$element[1]] = $nomArticle;
        }
    }
}


// Ajout de la liste des images présentes dans le répertoire du carrousel
$affichageListeFichiers ='';
$affichageListeFichiers .= '<span id=repertoire>';
$repertoire = "../donnees/fichiers";
    if ($dossier = opendir ($repertoire)) {
        while ( false !== ($file = readdir ( $dossier )) ) {
            $fileExtension = explode(".", $file)[count (explode(".", $file))-1];
            if ($fileExtension != "" && $file != '_fichier_defaut.pdf') {
                $affichageListeFichiers .= '<span class=texte><a target="_blank" href="'.$repertoire.'/'.$file.'">'.$file.'</a><br>(cliquez pour afficher ou télécharger le fichier)</span>';
                if (!array_key_exists($file, $listeFichiersStockes)){
                    $affichageListeFichiers .= '<span class=texte>Fichier non utilisé</span>';
                    $affichageListeFichiers .= '<br>';
                    $affichageListeFichiers .= '<a class=bouton_repertoire name=supprimer_fichier data-nomFichier="'.$file.'" data-nomRepertoire="'.$repertoire.'" href="">Supprimer ce fichier du répertoire</a>';
                }
                else {
                    $affichageListeFichiers .= '<span class=texte>Fichier à charger depuis la page "'.$listeFichiersStockes[$file].'".</span>';
                }
                $affichageListeFichiers .= '<br><br><br><br>';
            }
        }
        closedir ( $dossier );
    }
$affichageListeFichiers .='<br><br><br>';

$affichageListeFichiers .= '</span>';
echo $affichageListeFichiers;



