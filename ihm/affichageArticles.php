<?php
Function afficher_article($article) {
    include_once ('../donnees/balisesArticles.php');

    // Affichage de l'article mis en forme selon ses balises.
    foreach ( $article as $element ) {
        // Gestion des liens et fichiers qui comportent trois balises
        if (($element [0] == 'Fichier') || ($element [0] == 'Lien')) {
            // Récupération des balises d'ouverture, centrale et de fermeture
            $in = $balisesArticles [$element [0]] [0];
            $mid = $balisesArticles [$element [0]] [1];
            $out = $balisesArticles [$element [0]] [2];
            // Affichage de chaque élément
            $bloc = $in.$element[1].$mid.$element[1].$out;
        }
        // Gestion du carrousel qui est un ensemble de balise
        else if ($element [0] == 'Carrousel'){
            $bloc = $balisesArticles [$element [0]];
        }
        // ... pour tous les autres, deux balises suffisent
        else {
            // Récupération des balises d'ouverture et de fermeture
            $in = $balisesArticles [$element [0]] [0];
            $out = $balisesArticles [$element [0]] [1];
            // Affichage de chaque élément
            $bloc = $in.utf8_encode ($element[1]).$out;
        }
        echo $bloc;
    }
}

?>