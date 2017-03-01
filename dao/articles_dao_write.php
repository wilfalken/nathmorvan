<?php
// Lancement de session uniquement pour activer le stockage dans $_SESSION
session_start();


/* Récupération des données envoyées par le script Javascript :
 * - la fonction à utiliser (ici enregistrerArticle)
 * - le nom de l'article à modifier,
 * - l'article modifié en format JSON.
 */
if (!empty($_POST['nomArticleModifie']) && !empty($_POST['elementsArticle'])){
$fonction = $_POST['fonction'];
$nomArticleModifie = utf8_decode($_POST['nomArticleModifie']);
$elementsArticle = utf8_decode($_POST['elementsArticle']);
/* unset() détruit la ou les variables dont le nom a été passé en argument var.
 * Ce n'est pas indispensable, je pense qu'il s'agit d'une sécurité.
 */
unset($_POST['fonction']);
unset($_POST['nomArticleModifie']);
unset($_POST['elementsArticle']);
// Appel de la fonction
$fonction();
//$fonction($nomArticleModifie,$elementsArticle);
}

// Partie concernant l'enregistrement des noms d'articles
if (!empty($_POST['ancienNomArticle']) && !empty($_POST['nouveauNomArticle'])){
    $fonction = $_POST['fonction'];
    // Javascript envoie des données qui doivent être passées à la moulinette de l'UTF8 !
    $ancienNomArticle = utf8_decode($_POST['ancienNomArticle']);
    $nouveauNomArticle = utf8_decode ($_POST['nouveauNomArticle']);
    unset($_POST['fonction']);
    unset($_POST['ancienNomArticle']);
    unset($_POST['nouveauNomArticle']);
    $fonction($ancienNomArticle,$nouveauNomArticle);
}



Function renommerArticle ($ancienNomArticle,$nouveauNomArticle){
 
    // Reconstruction de l'objet générant la barre de menu
    $barreMenu = array();
    /* Le principe va consister à créer une nouvelle barre de menu.
     * Pour cela, on va parcourir l'ancienne et vérifier à chaque niveau
     * s'il faut conserver l'ancienne valeur ou mettre la valeur modifiée par l'utilisateur
     */
    foreach ($_SESSION['barre_menu'] as $nomMenu => $menu) {
        // Si le menu est un array ...
        if (gettype($menu) == 'array'){
            // ... ou bien il s'agit de la valeur à modifier ...
           if ($nomMenu == $ancienNomArticle){
               // ... et on a juste à remettre les anciens liens avec le nouveau nom de menu ...
                foreach ($menu as $lien) {
                    $barreMenu [$nouveauNomArticle][] = $lien;
                }
           }
           // ... ou bien la valeur à modifier est un des liens et il faut tous les vérifier un par un.
           else {
               foreach ($menu as $lien) {
                   if ($lien == $ancienNomArticle){
                       $barreMenu [$nomMenu][] = $nouveauNomArticle;
                   }
                   else {
                       $barreMenu [$nomMenu][] = $lien;
                   }
               }
           }
        }
        // Si le menu n'est pas un array ...
        else {
            // ... ou bien il s'agit de la valeur à modifier ...
            if ($nomMenu == $ancienNomArticle){
                $barreMenu [$nouveauNomArticle] = $nouveauNomArticle;
            }
            // ... ou on conserve l'ancienne valeur
            else {
                $barreMenu [$nomMenu] = $menu;
            }
        }
    }
    // Mise à jour de l'objet barre de menu et suppression de la variable de travail
    $_SESSION['barre_menu'] = $barreMenu;
    unset($barreMenu);
    
    
    
   // Reconstruction de la liste des liens autorisés
    $listeLiens = array();
    foreach ($_SESSION['listeLiens'] as $pageAutorisee) {
        if ($pageAutorisee == $ancienNomArticle){
            $listeLiens [] = $nouveauNomArticle;
        }
        else {
            $listeLiens [] = $pageAutorisee;
        }
    }
     // Mise à jour de l'objet lien qui contrôle les pages autorisées et suppression de la variable de travail
    $_SESSION['listeLiens'] = $listeLiens;
    unset($listeLiens);
    
    
    
    // Reconstruction de la liste des articles
    $listeArticles = array();
    foreach ($_SESSION['articles'] as $nomArticle => $article) {
        if ($nomArticle ==$ancienNomArticle ){
            $nomArticle = $nouveauNomArticle;
        }
        foreach ($article as $id => $element) {
            if ($id == 0){
                $listeArticles [$nomArticle][] = array ($element[0], $nomArticle);
            }
            else {
            $listeArticles [$nomArticle][] = array ($element[0], $element[1]);
            }
        }
    }
    // Mise à jour de l'objet articles et suppression de la variable de travail
    $_SESSION['articles'] = $listeArticles;
    unset($listeArticles);
    
    
    

    // ... pour ensuite l'enregistrer.
    saveXml ($_SESSION['articles'], $_SESSION['barre_menu']);
    // Réaffichage de la page afin de forcer la mise à jour des objets et de l'affichage
    header('Location: ihm/gestionArticles.php');
}




Function enregistrerArticle(){
    // include pour test
    include ('../dao/save.php');
    save( 'Date de la dernière sauvegarde : le '.date ( "d" ).'/'.date ( "m" ).'/'.date ( "Y" ).' à '.date ( "H" ).' heures '.date ( "i" ).' minutes et '.date ( "s" ).' secondes.');

    
    
    
    // Réception de la méthode POST avec fonction (texte), nomArticleModifie  (texte) et elementsArticle (JSON)

    // TODO
    // 1 transformer le JSON en array
    // 2 modifier le $articles[nomArticleModifie] = array

    // Appel de la fonction de sauvegarde qui définie juste dessous
    saveXml ($_SESSION['articles'], $_SESSION['barre_menu']);
}



// D�finition de la fonction de sauvegarde
Function saveXml($articles,$barre_menu) {
    
	$xml_articles_output = new DOMImplementation ();
	// Création d'une instance DOMDocumentType (dtd)
	$dtd = $xml_articles_output->createDocumentType ( 'barre_menu', '', '../donnes/dtd/articles.dtd' );
	// Création d'une instance DOMDocument
	$root = $xml_articles_output->createDocument ( "", "", $dtd );
	$root->encoding = "utf-8";
	
	// Gestion de l'affichage (passage à la ligne à chaque noeud enfant de la racine
	$root->formatOutput = true;
	
	// Création de la racine et ajout au document
	$barre_menu_node = $root->createElement ( 'barre_menu' );
	$root->appendChild ( $barre_menu_node );
	
	// On parcourt la barre de menu
	foreach ( $barre_menu as $nomMenu => $menu ) {
		// On créé donc un noeud 'menu' ...
		$menu_node = $root->createElement ( 'menu' );
		// ... relié à son noeud parent ...
		$barre_menu_node->appendChild ( $menu_node );
		// S'il s'agit d'un lien direct ...
		if (gettype ( $menu ) == 'string') {
			// ... alors, il s'agit d'un article et non d'un menu
			// ... et on lui ajoute son nom sous forme d'attribut.
			$menu_node->setAttribute ( 'nom_menu', utf8_encode ( $menu ) );
			// ... alors, il s'agit d'un article et non d'un menu
			$article = $menu;
			/*
			 * ... puis utilisation d'une fonction créant des noeuds
			 * et leurs contenus et attributs pour les éléments de chaque article
			 */
			articleToXml ( $article, $articles, $menu_node, $root );
		}  // Sinon, il s'agit d'un menu avec un nom et comportant plusieurs liens
		else if (gettype ( $menu ) == 'array'){
			// ... et on lui ajoute son nom sous forme d'attribut.
			$menu_node->setAttribute ( 'nom_menu', utf8_encode ( $nomMenu ) );
			// Puis on parcourt le menu afin de récupérer les liens vers les articles
			foreach ( $menu as $article ) {
				articleToXml ( $article, $articles, $menu_node, $root );
			}
		}
		// Enregistrement du fichier
		$root->save ( '../donnees/xml/articles.xml' );
		// Création d'un point de sauvegarde
		$root->save ( '../donnees/xml/autoback/articles_' . date ( "Y-m-d_H-i-s" ) . '.xml' );
	}
}

// Fonction permettant de créer des noeuds xml pour un article
Function articleToXml($article, $articles, $menu_node, $root) {

	// Création d'un noeud 'article' ...
	$article_node = $root->createElement ( 'article' );
	// ... relié à son noeud parent
	$menu_node->appendChild ( $article_node );
	$nomArticle_node = $root->createElement ( 'nom' );
	$article_node->appendChild ( $nomArticle_node );
	$contenu = $root->createTextNode ( utf8_encode ( $article ) );
	$nomArticle_node->appendChild ( $contenu );
	$articleCourant = $articles [$article];
	/* Si l'article vient d'être créé, il ne comporte aucun élément
	 * Hors, en mode admin, il faut qu'il y ait au mieux un élément
	 * pour pouvoir en ajouter d'autres.
	 * On va donc forcer en écriture la création d'un élément.
	 */
	if (count($articleCourant)==1){
		$element_node = $root->createElement ( 'element' );
		$article_node->appendChild ( $element_node );
		// Ajout de l'attribut de balise
		$element_node->setAttribute ( 'balise', utf8_encode ('Paragraphe') );
		// Ajout du texte
		$contenu = $root->createTextNode ( utf8_encode ( 'Article en cours de r�daction' ) );
		$element_node->appendChild ( $contenu );
	}
	/* Si l'article comporte déjà des éléments,
	 * on va le serializer en bouclant dans ses éléments.
	 */
	else {
		foreach ( $articleCourant as $element ) {
			/*
			 * On n'enregistre pas le premier élément :
			 * il s'agit du titre de l'article
			 */
			if ($element [0] != 'Titre article') {
				$element_node = $root->createElement ( 'element' );
				$article_node->appendChild ( $element_node );
				// Ajout de l'attribut de balise
				$element_node->setAttribute ( 'balise', utf8_encode ($element [0]) );
				// Ajout du texte
				$contenu = $root->createTextNode ( utf8_encode ( $element [1] ) );
				$element_node->appendChild ( $contenu );
			}
		}
	}
}

?>