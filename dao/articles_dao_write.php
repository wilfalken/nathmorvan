<?php

if (!empty($_POST['fonction']) && $_POST['fonction'] == 'gestionArticle') {
    // Javascript envoie des données qui doivent être passées à la moulinette de l'UTF8
    $nomArticle = utf8_decode($_POST['nomArticle']);
    $nomMenu = utf8_decode($_POST['nomMenu']);
    $actionBouton = utf8_decode($_POST['actionBouton']);
    $idArticle = utf8_decode($_POST['idArticle']);
    $idMenu = utf8_decode($_POST['idMenu']);
    $modification = utf8_decode($_POST['modification']);
    unset($_POST['fonction']);
    unset($_POST['nomArticle']);
    unset($_POST['nomMenu']);
    unset($_POST['action']);
    unset($_POST['idArticle']);
    unset($_POST['idMenu']);
    unset($_POST['modification']);


    include '../dao/save.php';
    save('article ' . $idArticle . ' : ' . $nomArticle . ' | menu ' . $idMenu . ' : ' . $nomMenu . ' | action : ' . $action . ' | modification : ' . $modification);




    switch ($actionBouton) {




        case 'modificationNomArticle':
            // Modification du nom dans la barre de menu
            $_SESSION['barre_menu'][$idMenu][1][$idArticle] = $modification;

            // Modification de la liste des liens autorisés
            foreach ($_SESSION['listeLiens'] as $id => $pageAutorisee) {
                if ($pageAutorisee == $nomArticle) {
                    $_SESSION['listeLiens'] [$id] = $modification;
                }
            }

            // Modification de la liste des articles
            $sauvegardeArticle = $_SESSION['articles'][$nomArticle];
            // Mise à jour du titre de l'article
            $sauvegardeArticle[0][1] = $modification;
            // Suppression de l'article avec l'ancien nom
            unset($_SESSION['articles'][$nomArticle]);
            // Ajout de l'article avec le nouveau nom
            $_SESSION['articles'][$modification] = $sauvegardeArticle;
            unset($modification);

            break;




        case 'modificationNomMenu':
            // Mise à jour du menu
            $_SESSION['barre_menu'][$idMenu][0] = $modification;
            // Le menu ne compte qu'un élément, on modifie aussi le nom de l'article associé

            if (count($_SESSION['barre_menu'][$idMenu][1]) == 1) {
                // Modification du nom dans la barre de menu
                $_SESSION['barre_menu'][$idMenu][1][$idArticle] = $modification;

                // Modification de la liste des liens autorisés
                foreach ($_SESSION['listeLiens'] as $id => $pageAutorisee) {
                    if ($pageAutorisee == $nomArticle) {
                        $_SESSION['listeLiens'] [$id] = $modification;
                    }
                }

                // Modification de la liste des articles
                $sauvegardeArticle = $_SESSION['articles'][$nomArticle];
                // Mise à jour du titre de l'article
                $sauvegardeArticle[0][1] = $modification;
                // Suppression de l'article avec l'ancien nom
                unset($_SESSION['articles'][$nomArticle]);
                // Ajout de l'article avec le nouveau nom
                $_SESSION['articles'][$modification] = $sauvegardeArticle;
                unset($modification);
            }

            break;




        case 'suppressionArticle':
            // Afin d'avoir un index sans trou, on recréé la liste d'article
            $menuTemp = array();
            foreach ($_SESSION['barre_menu'][$idMenu][1] as $id => $element) {
                if ($id != $idArticle) {
                    $menuTemp [] = $element;
                }
            }
            $_SESSION['barre_menu'][$idMenu][1] = $menuTemp;

            // Suppression de l'article de la liste des liens
            foreach ($listeArticles as $id => $value) {
                if ($value == $nomArticle) {
                    unset($_SESSION['listeLiens'][$id]);
                }
            }

            break;




        case 'suppressionMenu':
            // Afin d'avoir un index sans trou, on recréé la liste de menu
            $barreMenuTemp = array();
            foreach ($_SESSION['barre_menu'] as $id => $element) {
                if ($id != $idMenu) {
                    $barreMenuTemp [] = $element;
                }
            }
            $_SESSION['barre_menu'] = $barreMenuTemp;

            // S'il s'agit d'un lien direct, on supprime le lien de la liste des liens autorisés
            if (count($_SESSION['barre_menu'][$idMenu][0]) == 1) {
                foreach ($listeArticles as $id => $value) {
                    if ($value == $nomArticle) {
                        unset($_SESSION['listeLiens'][$id]);
                    }
                }
            }
            break;




        case 'deplacerArticleVersHaut':
            if ($idArticle > 0) {
                $articlePrecTemp = $_SESSION['barre_menu'][$idMenu][1][$idArticle - 1];
                $articleTemp = $_SESSION['barre_menu'][$idMenu][1][$idArticle];
                $_SESSION['barre_menu'][$idMenu][1][$idArticle - 1] = $articleTemp;
                $_SESSION['barre_menu'][$idMenu][1][$idArticle] = $articlePrecTemp;
                unset($articleTemp);
                unset($articlePrecTemp);
            }
            break;




        case 'deplacerArticleVersBas':
            if ($idArticle < (count($_SESSION['barre_menu'][$idMenu][1]) - 1)) {
                $articleSuivTemp = $_SESSION['barre_menu'][$idMenu][1][$idArticle + 1];
                $articleTemp = $_SESSION['barre_menu'][$idMenu][1][$idArticle];
                $_SESSION['barre_menu'][$idMenu][1][$idArticle + 1] = $articleTemp;
                $_SESSION['barre_menu'][$idMenu][1][$idArticle] = $articleSuivTemp;
                unset($articleTemp);
                unset($articleSuivTemp);
            }
            break;




        case 'deplacerMenuVersHaut':
            if ($idMenu > 0) {
                $menuTemp = $_SESSION['barre_menu'][$idMenu];
                $menuPrecTemp = $_SESSION['barre_menu'][$idMenu - 1];
                $_SESSION['barre_menu'][$idMenu - 1] = $menuTemp;
                $_SESSION['barre_menu'][$idMenu] = $menuPrecTemp;
                unset($menuTemp);
                unset($menuPrecTemp);
            }
            break;




        case 'deplacerMenuVersBas':
            if ($idMenu < (count($_SESSION['barre_menu']) - 1)) {
                $menuTemp = $_SESSION['barre_menu'][$idMenu];
                $menuSuivTemp = $_SESSION['barre_menu'][$idMenu + 1];
                $_SESSION['barre_menu'][$idMenu + 1] = $menuTemp;
                $_SESSION['barre_menu'][$idMenu] = $menuSuivTemp;
                unset($menuTemp);
                unset($menuSuivTemp);
            }
            break;




        case 'ajouterArticleAuDessus':
            $menuTemp = array();
            for ($id = 0; $id <= count($_SESSION['barre_menu'][$idMenu][1]); $id++) {
                if ($id < $idArticle) {
                    $menuTemp [] = $_SESSION['barre_menu'][$idMenu][1][$id];
                } else if ($id == $idArticle) {
                    $menuTemp [] = $elementAjoute;
                } else {
                    $menuTemp [] = $_SESSION['barre_menu'][$idMenu][1][$id - 1];
                }
            }
            $_SESSION['barre_menu'][$idMenu][1] = $menuTemp;
            unset($menuTemp);

            // Ajout d'un premier élément au nouvel article
            $_SESSION['articles'][$elementAjoute][] = array('Paragraphe', 'Article en cours de rédaction');
            
            // Ajout de l'article à la liste des liens
            $_SESSION['listeLiens'][] = $modification;
            break;




        case 'ajouterArticleAuDessous':
            $menuTemp = array();
            for ($id = 0; $id <= count($_SESSION['barre_menu'][$nomMenu][1]); $id++) {
                if ($id <= $idArticle) {
                    $menuTemp [] = $_SESSION['barre_menu'][$idMenu][1][$id];
                } else if ($id == $idArticle + 1) {
                    $menuTemp [] = $elementAjoute;
                } else {
                    $menuTemp [] = $_SESSION['barre_menu'][$idMenu][1][$id - 1];
                }
            }
            $_SESSION['barre_menu'][$idMenu][1] = $menuTemp;
            unset($menuTemp);

            // Ajout d'un premier élément au nouvel article
            $_SESSION['articles'][$elementAjoute][] = array('Paragraphe', 'Article en cours de rédaction');
            
            // Ajout de l'article à la liste des liens
            $_SESSION['listeLiens'][] = $modification;
            break;




        case 'ajouterMenuAuDessus':
            $barreMenuTemp = array();
            for ($id = 0; $id <= count($_SESSION['barre_menu']); $id++) {
                if ($id < $idMenu) {
                    $menuTemp [] = $_SESSION['barre_menu'][$id];
                } else if ($id == $idMenu) {
                    $barreMenuTemp [] = array($modification, array($modification));
                } else {
                    $menuTemp [] = $_SESSION['barre_menu'][$id]+1;
                }
            }
            $_SESSION['barre_menu'] = $barreMenuTemp;
            unset($barreMenuTemp);
            
            // Ajout de l'article à la liste des liens
            $_SESSION['listeLiens'][] = $modification;
            break;




        case 'ajouterMenuAuDessous':
            $barreMenuTemp = array();
            for ($id = 0; $id <= count($_SESSION['barre_menu']); $id++) {
                if ($id <= $idMenu) {
                    $menuTemp [] = $_SESSION['barre_menu'][$id];
                } else if ($id == $idMenu + 1) {
                    $barreMenuTemp [] = array($modification, array($modification));
                } else {
                    $menuTemp [] = $_SESSION['barre_menu'][$id+1];
                }
            }
            $_SESSION['barre_menu'] = $barreMenuTemp;
            unset($barreMenuTemp);
            
            // Ajout de l'article à la liste des liens
            $_SESSION['listeLiens'][] = $modification;
            break;
    }
    // Enregistrement
    saveXml($_SESSION['articles'], $_SESSION['barre_menu']);
    // Réaffichage de la page afin de forcer la mise à jour des objets et de l'affichage
    header('Location: ../admin/index.php');
}



// Partie concernant l'enregistrement des modification d'un élément d'un article
if (!empty($_POST['fonction']) && $_POST['fonction'] == 'enregistrerElementModifie') {
    // Javascript envoie des données qui doivent être passées à la moulinette de l'UTF8
    $nomArticleModifie = utf8_decode($_POST['nomArticleModifie']);
    $idElementModifie = utf8_decode($_POST['idElementModifie']);
    $actionElement = utf8_decode($_POST['actionElement']);
    $elementModifie = utf8_decode($_POST['elementModifie']);
    $baliseModifiee = utf8_decode($_POST['baliseModifiee']);
    unset($_POST['fonction']);
    unset($_POST['nomArticleModifie']);
    unset($_POST['idElementModifie']);
    unset($_POST['actionModifie']);
    unset($_POST['elementModifie']);
    unset($_POST['baliseModifiee']);
    // Appel de la fonction d'enregistrement de la modification d'un élément
    enregistrerElementModifie($nomArticleModifie, $idElementModifie, $actionElement, $elementModifie, $baliseModifiee);
}

Function enregistrerElementModifie($nomArticleModifie, $idElementModifie, $actionElement, $elementModifie, $baliseModifiee) {


    switch ($actionElement) {
        case 'bouton_supprimer':
            /* Suppression de l'élément sauf s'il s'agit du dernier élément modifiable
             * (donc deux en comptant le titre qui n'est pas modifiable).
             */
            if (count($_SESSION['articles'][$nomArticleModifie]) != 2) {
                /* En réalité, on va pas supprimer l'élément,
                 * on va l'écraser par le suivant et supprimer le dernier,
                 * ceci afin de ne pas avoir de "trou" dans l'index des éléments de l'article modifié.
                 */
                $articleModifieTemp = array();
                foreach ($_SESSION['articles'][$nomArticleModifie] as $id => $element) {
                    if ($id != $idElementModifie) {
                        $articleModifieTemp [] = $element;
                    }
                }
                $_SESSION['articles'][$nomArticleModifie] = $articleModifieTemp;
                unset($articleModifieTemp);
            }
            break;


        case 'bouton_deplacerHaut':
            // Inversion $idElementModifie et $idElementModifie-1
            $elementTemporaire = $_SESSION['articles'][$nomArticleModifie][$idElementModifie];
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie] = $_SESSION['articles'][$nomArticleModifie][$idElementModifie - 1];
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie - 1] = $elementTemporaire;
            break;


        case 'bouton_deplacerBas':
            // Inversion $idElementModifie et $idElementModifie+1
            $elementTemporaire = $_SESSION['articles'][$nomArticleModifie][$idElementModifie];
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie] = $_SESSION['articles'][$nomArticleModifie][$idElementModifie + 1];
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie + 1] = $elementTemporaire;
            break;


        case 'bouton_valider_modification':
            // Mise à jour de l'élément
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie][1] = $elementModifie;
            break;


        case 'bouton_ajouterDessus':
            // Ajout d'un élément temporaire au-dessus qui ne sera pas dans le XML
            $nombreElementDansArticle = count($_SESSION['articles'][$nomArticleModifie]);
            for ($i = $nombreElementDansArticle; $i > $idElementModifie; $i--) {
                $_SESSION['articles'][$nomArticleModifie][$i] = $_SESSION['articles'][$nomArticleModifie][$i - 1];
            }
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie] = ['temp', 'temp'];
            break;


        case 'bouton_ajouterDessous':
            // Ajout d'un élément temporaire ou au-dessous qui ne sera pas dans le XML
            $nombreElementDansArticle = count($_SESSION['articles'][$nomArticleModifie]);
            for ($i = $nombreElementDansArticle; $i > $idElementModifie + 1; $i--) {
                $_SESSION['articles'][$nomArticleModifie][$i] = $_SESSION['articles'][$nomArticleModifie][$i - 1];
            }
            $_SESSION['articles'][$nomArticleModifie][$idElementModifie + 1] = ['temp', 'temp'];
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
    saveXml($_SESSION['articles'], $_SESSION['barre_menu']);
}

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
        $menu_node->setAttribute('nom_menu', utf8_encode($menu[0]));
        // Puis on parcourt le menu afin de récupérer les liens vers les articles
        foreach ($menu[1] as $article) {
            articleToXml($article, $articles, $menu_node, $root);
        }
        // Enregistrement du fichier
        $root->save('../donnees/xml/articles.xml');
        // Création d'un point de sauvegarde
        $root->save('../donnees/xml/autoback/articles_' . date("Y-m-d_H-i-s") . '.xml');
    }
}

// Fonction permettant de créer des noeuds xml pour un article
Function articleToXml($article, $articles, $menu_node, $root) {

    // Création d'un noeud 'article' ...
    $article_node = $root->createElement('article');
    // ... relié à son noeud parent
    $menu_node->appendChild($article_node);
    $nomArticle_node = $root->createElement('nom');
    $article_node->appendChild($nomArticle_node);
    $contenu = $root->createTextNode(utf8_encode($article));
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
        $element_node->setAttribute('balise', utf8_encode('Paragraphe'));
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
                    $element_node->setAttribute('balise', utf8_encode($element [0]));
                    // Ajout du texte
                    $contenu = $root->createTextNode(utf8_encode($element [1]));
                    $element_node->appendChild($contenu);
                }
            }
        }
    }
}

?>