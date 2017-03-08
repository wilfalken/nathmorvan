<?php
/* Lancement de session uniquement pour activer le stockage dans $_SESSION.
 * On est obligé de le lancer car ce fichier n'est pas inclu dans index.php,
 * il est appelé depuis le côté client par une fonction Ajax.
 * Comme ce fichier est également en include dans les upload, ajout d'un @ pour ne pas afficher l'erreur.
 */
@session_start();


// Partie concernant l'enregistrement des modification d'un élément d'un article
if (!empty($_POST['nomArticleModifie']) && !empty($_POST['idElementModifie']) && !empty($_POST['actionElement'])){
 
    $fonction = $_POST['fonction'];
    $nomArticleModifie = utf8_decode($_POST['nomArticleModifie']);
    $idElementModifie = utf8_decode($_POST['idElementModifie']);
    $actionElement = utf8_decode($_POST['actionElement']);
    $elementModifie = utf8_decode($_POST['elementModifie']);
    $baliseModifiee = utf8_decode($_POST['baliseModifiee']);
    /* unset() détruit la ou les variables dont le nom a été passé en argument var.
     * Ce n'est pas indispensable, je pense qu'il s'agit d'une sécurité.
     */
    unset($_POST['fonction']);
    unset($_POST['nomArticleModifie']);
    unset($_POST['idElementModifie']);
    unset($_POST['actionModifie']);
    unset($_POST['elementModifie']);
    unset($_POST['baliseModifiee']);
    // Appel de la fonction d'enregistrement de la modification d'un élément
    $fonction($nomArticleModifie,$idElementModifie,$actionElement,$elementModifie,$baliseModifiee);
}



// Partie concernant l'enregistrement des noms d'articles
if (!empty($_POST['ancienNomArticle']) && !empty($_POST['nouveauNomArticle'])){
    $fonction = $_POST['fonction'];
    // Javascript envoie des données qui doivent être passées à la moulinette de l'UTF8
    $ancienNomArticle = utf8_decode($_POST['ancienNomArticle']);
    $nouveauNomArticle = utf8_decode ($_POST['nouveauNomArticle']);
    unset($_POST['fonction']);
    unset($_POST['ancienNomArticle']);
    unset($_POST['nouveauNomArticle']);
    // Appel de la fonction d'enregistrement du nouveau nom d'article
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




Function enregistrerElementModifie($nomArticleModifie,$idElementModifie,$actionElement,$elementModifie,$baliseModifiee){

    
    switch ($actionElement) {
        case 'bouton_supprimer':
            /* Suppression de l'élément sauf s'il s'agit du dernier élément modifiable
             * (donc deux en comptant le titre qui n'est pas modifiable).
             */
            if (count ($_SESSION['articles'][$nomArticleModifie])!=2){
                /* En réalité, on va pas supprimer l'élément,
                 * on va l'écraser par le suivant et supprimer le dernier,
                 * ceci afin de ne pas avoir de "trou" dans l'index des éléments de l'article modifié.
                 */
                $articleModifieTemp = array();
                foreach ($_SESSION['articles'][$nomArticleModifie] as $id => $element) {
                    if ($id != $idElementModifie){
                        $articleModifieTemp [] = $element;
                    }
                }
                $_SESSION['articles'][$nomArticleModifie] = $articleModifieTemp;
                unset ($articleModifieTemp);
                /*
                $nombreElementDansArticle = count($_SESSION['articles'][$nomArticleModifie]);
                for ($i = $idElementModifie; $i > $nombreElementDansArticle; $i++) {
                    $_SESSION['articles'][$nomArticleModifie][$i] = $_SESSION['articles'][$nomArticleModifie][$i+1];        
                }
                unset ($_SESSION['articles'][$nomArticleModifie][$nombreElementDansArticle-1]);
                 * 
                 */
            }
            break;
            
            
        case 'bouton_deplacerHaut':
            // Inversion $idElementModifie et $idElementModifie-1
            $elementTemporaire = $_SESSION['articles'][$nomArticleModifie][$idElementModifie];
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie] = $_SESSION['articles'][$nomArticleModifie][$idElementModifie-1];
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie-1] = $elementTemporaire;
            break;
        
        
        case 'bouton_deplacerBas':
            // Inversion $idElementModifie et $idElementModifie+1
            $elementTemporaire = $_SESSION['articles'][$nomArticleModifie][$idElementModifie];
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie] = $_SESSION['articles'][$nomArticleModifie][$idElementModifie+1];
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie+1] = $elementTemporaire;
            break;
        
        
        case 'bouton_valider_modification':
            // Mise à jour de l'élément
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie][1] = $elementModifie;
            break;
        
        
        case 'bouton_ajouterDessus':
            // Ajout d'un élément temporaire au-dessus qui ne sera pas dans le XML
            $nombreElementDansArticle = count($_SESSION['articles'][$nomArticleModifie]);
            for ($i = $nombreElementDansArticle; $i > $idElementModifie; $i--) {
                $_SESSION['articles'][$nomArticleModifie][$i] = $_SESSION['articles'][$nomArticleModifie][$i-1];        
            }    
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie]=['temp','temp'];
            break;
            
            
        case 'bouton_ajouterDessous':
            // Ajout d'un élément temporaire ou au-dessous qui ne sera pas dans le XML
            $nombreElementDansArticle = count($_SESSION['articles'][$nomArticleModifie]);
            for ($i = $nombreElementDansArticle; $i > $idElementModifie+1; $i--) {
                $_SESSION['articles'][$nomArticleModifie][$i] = $_SESSION['articles'][$nomArticleModifie][$i-1];        
            }    
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie+1]=['temp','temp'];
            break;
            
            
        case 'bouton_valider_insertion':
            /* Mise à jour de l'élément (balise et contenu)
             * en fonction des choix de l'utilisateur
             * réalisés dans le DOM.
             */
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie][0] = $baliseModifiee;
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie][1] = $elementModifie;
            break;

    }
    


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
		$contenu = $root->createTextNode (  'Article en cours de rédaction'  );
		$element_node->appendChild ( $contenu );
	}
	/* Si l'article comporte déjà des éléments,
	 * on va le serializer en bouclant dans ses éléments.
	 */
	else {
		foreach ( $articleCourant as $element ) {
			/*
			 * On n'enregistre pas le premier élément :
			 * il s'agit du titre de l'article.
                         * On n'enregistre pas non plus les éléments temporaires.
			 */
			if ($element [0] != 'Titre article') {
                            if ($element [0] != 'temp'){
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
}

?>