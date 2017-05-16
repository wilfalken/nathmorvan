<?php

// Définition de la fonction de sauvegarde
Function saveXml($articles, $barre_menu) {

    $xml_articles_output = new DOMImplementation ();
    // Création d'une instance DOMDocumentType (dtd)
    $dtd = $xml_articles_output->createDocumentType('barre_menu', '', '../donnes/dtd/articles.dtd');
    // Création d'une instance DOMDocument
    $root = $xml_articles_output->createDocument("", "", $dtd);
    $root->encoding = "utf-8";

    // Gestion de l'affichage (passage à la ligne à chaque noeud enfant de la racine
    $root->formatOutput = true;

    // Création de la racine et ajout au document
    $barre_menu_node = $root->createElement('barre_menu');
    $root->appendChild($barre_menu_node);

    // On parcourt la barre de menu
    foreach ($barre_menu as $menu) {
        // On créé donc un noeud 'menu' ...
        $menu_node = $root->createElement('menu');
        // ... relié à son noeud parent ...
        $barre_menu_node->appendChild($menu_node);
        // S'il s'agit d'un lien direct ...
        // ... et on lui ajoute son nom sous forme d'attribut.
        $menu_node->setAttribute('nom_menu', ($menu[0]));
        // Puis on parcourt le menu afin de récupérer les liens vers les articles
        foreach ($menu[1] as $article) {
            // Création d'un noeud 'article' ...
            $article_node = $root->createElement('article');
            // ... relié à son noeud parent
            $menu_node->appendChild($article_node);
            $nomArticle_node = $root->createElement('nom');
            $article_node->appendChild($nomArticle_node);
            $contenu = $root->createTextNode(($article));
            $nomArticle_node->appendChild($contenu);
            $articleCourant = $articles [$article];
            /* Si l'article vient d'être créé, il ne comporte aucun élément
             * Hors, en mode admin, il faut qu'il y ait au mieux un élément
             * pour pouvoir en ajouter d'autres.
             * On va donc forcer en écriture la création d'un élément.
             */
            if (count($articleCourant) == 1) {
                $element_node = $root->createElement('element');
                $article_node->appendChild($element_node);
                // Ajout de l'attribut de balise
                $element_node->setAttribute('balise', ('Paragraphe'));
                // Ajout du texte
                $contenu = $root->createTextNode('Article en cours de rédaction');
                $element_node->appendChild($contenu);
            }
            /* Si l'article comporte déjà des éléments,
             * on va le serializer en bouclant dans ses éléments.
             */ else {
                foreach ($articleCourant as $element) {
                    /*
                     * On n'enregistre pas le premier élément :
                     * il s'agit du titre de l'article.
                     * On n'enregistre pas non plus les éléments temporaires.
                     */
                    if ($element [0] != 'Titre article') {
                        if ($element [0] != 'temp') {
                            $element_node = $root->createElement('element');
                            $article_node->appendChild($element_node);
                            // Ajout de l'attribut de balise
                            $element_node->setAttribute('balise', ($element [0]));
                            // Ajout du texte
                            $contenu = $root->createTextNode(($element [1]));
                            $element_node->appendChild($contenu);
                        }
                    }
                }
            }
        }
        // Enregistrement du fichier
        $root->save('../donnees/xml/articles.xml');
        // Création d'un point de sauvegarde
        $root->save('../donnees/xml/autoback/articles_' . date("Y-m-d_H-i-s") . '.xml');
    }
}



?>