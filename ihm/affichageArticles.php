<?php
Function afficher_article($article) {
    include_once ('../donnees/balisesArticles.php');

    // Affichage de l'article mis en forme selon ses balises.
    foreach ( $article as $element ) {
        // Gestion des liens et fichiers qui comportent trois balises
        if (($element [0] == 'Fichier') || ($element [0] == 'Lien') || ($element [0] == 'Image')) {
            // Si c'est un fichier, on affiche le nom sans l'identifiant et le type de fichier (extension)
            if ($element [0] == 'Fichier'){
                $texteVisible = explode("_[",$element[1])[0]." - Fichier de type ". explode("].",$element[1])[1];
            }
            else {
                $texteVisible = $element[1];
            }
            // Récupération des balises d'ouverture, centrale et de fermeture
            $in = $balisesArticles [$element [0]] [0];
            $mid = $balisesArticles [$element [0]] [1];
            $out = $balisesArticles [$element [0]] [2];
            // Affichage de chaque élément
            $bloc = $in.$element[1].$mid.$texteVisible.$out;
        }
        // Gestion du carrousel qui est un ensemble de balise
        else if ($element [0] == 'Carrousel' || $element [0] == 'GoogleMap'){
            $bloc = $balisesArticles [$element [0]];
        }
        // ... pour tous les autres, deux balises suffisent
        else {
            // Récupération des balises d'ouverture et de fermeture
            $in = $balisesArticles [$element [0]] [0];
            $out = $balisesArticles [$element [0]] [1];
            // Affichage de chaque élément
            $bloc = $in. ($element[1]).$out;
        }
        echo $bloc;
    }
}

?>