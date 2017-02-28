<?php
Function afficher_article($article) {
	include_once ('../donnees/balisesArticles.php');
	
	// Affichage de l'article mis en forme selon ses balises.
	foreach ( $article as $element ) {
		// Gestion des liens qui comporte trois balises
		if ($element [0] == 'Lien') {
			// Récupération des balises d'ouverture, centrale et de fermeture
			$in = $balisesArticles [$element [0]] [0];
			$mid = $balisesArticles [$element [0]] [1];
			$out = $balisesArticles [$element [0]] [2];
			// On ne va pas afficher le chemin complet, juste le nom et l'extension
			$tableauLien = explode('/', $element [1]);
			$nomLien = $tableauLien[count($tableauLien)-1];
			// Affichage de chaque élément
			$bloc = $in.$element[1].$mid.$nomLien.$out;
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