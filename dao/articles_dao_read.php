<?php




Function getXml() {
	$articles = array();
	$barre_menu = array();
	$listeLiens = array();
	// Création d'une variable de chargement du fichier
	$xml_barre_menu = simplexml_load_file ( '../donnees/xml/articles.xml' );
	
	/* Début de la boucle de parcours du fichier XML au niveau des menus
	 * On va être obligé de parcourir par la suite deux fois chaque élément
	 * (à cause des liens directs).
	 * A optimiser.
	 */
	foreach ( $xml_barre_menu->menu as $xml_menu ) {
		
		
		// Partie création menu et liste des articles pour test adresse
		$listeElementsMenu = null;
		// S'il n'y a qu'un élément, on le met à la racine ...
		if (count ( $xml_menu ) == 1) {
			$barre_menu [] = utf8_decode ( $xml_menu ['nom_menu'] );
			$listeLiens [] = utf8_decode ( $xml_menu ['nom_menu'] );
			// Sinon, on créé un menu, définit par son nom et ses liens.
		} else {
			$nomMenu = utf8_decode ( $xml_menu ['nom_menu'] );
			foreach ( $xml_menu->children () as $xml_article ) {
				$listeElementsMenu [] = utf8_decode ( $xml_article->nom );
				$listeLiens []= utf8_decode ( $xml_article->nom );
			}
			$barre_menu [$nomMenu] = $listeElementsMenu;
		}
		
		// Partie création articles
		foreach ( $xml_menu->article as $xml_article ) {
			/*
			 * Ajout d'un nouvel article à la liste des articles
			 * en récupérant son nom.
			 */
			$nomArticle = utf8_decode ( $xml_article->nom );
			/*
			 * On remet à zéro la liste des éléments,
			 * sinon ceux de l'article précédnet seront ajoutés.
			 */
			$listeElementsArticle = null;
			// On ajoute le titre en tant que premier élément de l'article
			$listeElementsArticle [] = array (
					'Titre article',
					$nomArticle 
			);
			// Puis on ajoute les autres éléments
			foreach ( $xml_article->element as $xml_element ) {
				/*
				 * Pour chaque élément, on ajoute un ligne au tableau.
				 * Les attributs et le contenu doivent être écrasés
				 * en string (strval), sinon ils sont considérés
				 * comme objet et ne peuvent être utilisé comme clé dans les arrays.
				 */
				$baliseElement = utf8_decode ( $xml_element ['balise'] );
				$contenuElement = utf8_decode ( $xml_element );
				$listeElementsArticle [] = array (
						$baliseElement,
						$contenuElement 
				);
			}
			$articles [$nomArticle] = $listeElementsArticle;
		}
	}
        /* Les variables sont stockées dans $_SESSION
         * afin qu'il n'y ait qu'une deserialization au moment de l'accès au site
         * et non à chaque rechargement de la page.
         */
        $_SESSION['articles'] = $articles;
        $_SESSION['barre_menu'] = $barre_menu;
        $_SESSION['listeLiens'] = $listeLiens;
}



?>