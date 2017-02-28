<?php
Function afficher_modifier_article($article) {
	include_once ('../donnees/balisesArticles.php');
	
	// Affichage de l'article mis en forme selon ses balises.
	foreach ( $article as $id =>$element ) {
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
			$bloc = $in.$element [1].$mid.$nomLien.$out;
		} 		
		// ... pour tous les autres, deux balises suffisent
		else {
			// Récupération des balises d'ouverture et de fermeture
			$in = $balisesArticles [$element [0]] [0];
			$out = $balisesArticles [$element [0]] [1];
			// Affichage de chaque élément
			$bloc = $in.$element [1].$out;
		}
		/* Si ce n'est pas un Titre article (h1),
		 * on met le bloc dans un span et on ajoute
		 * les boutons de modification.
		 * On ne traite pas le titre de l'article,
		 * il est modifiable par la gestion du nombre d'article.
		 */
		if ($element [0] != 'Titre article'){
			$bloc = '<div class=element data-numero='.$id.'><span class=texteAffiche>'.utf8_encode($bloc).'</span>';
			// Gestion d'un espace supplémentaire pour les liens
			if ($element [0] == 'Lien'){
			$bloc .='<br><br>';
			}
			$bloc .='<span class=texteModifiable><textarea COLS="100">'.utf8_encode($element [1]).'</textarea></span>';

			/* Ajout des boutons de modification
			 * Ceux-ci sont des liens mais il ne pointent vers rien :
			 * leur action est traitée dans script.js (js.php) en fonction de leur classe.
			 */
			$bloc .='<span class=boutons_modifications>
					<a class=bouton name=bouton_modifier href="">Modifier</a>
					<a class=bouton name=bouton_supprimer href="">Supprimer</a>
					<a class=bouton name=bouton_ajouterDessus href="">Aj. au-dessus</a>
					<a class=bouton name=bouton_ajouterDessous href="">Aj. au-dessous</a>
					<a class=bouton name=bouton_deplacerHaut href="">Dép. vers le haut</a>
					<a class=bouton name=bouton_deplacerBas href="">Dép. vers le bas</a>
					</span>';
			$bloc .='<span class=boutons_validation>
					<a class=bouton name=bouton_valider_modification href="">Enregistrer</a>
					<a class=bouton name=bouton_annuler_modification href="">Annuler</a>
					</span>';
			$bloc .='<span class=insertion>
                                <br><select name=type>';
                                foreach ($balisesArticles as $nomBalise => $balises){
                                    $bloc .= '<option>'.$nomBalise.'</option>';
                                }
                        $bloc .='        </select><br><br>
                                <a class=bouton name=bouton_valider_insertion href="">Valider le type</a>
                                <a class=bouton name=bouton_supprimer href="">Annuler</a>
                                </span>';		
			
			$bloc .='</div>';
		}
		echo $bloc;
	}
}

?>