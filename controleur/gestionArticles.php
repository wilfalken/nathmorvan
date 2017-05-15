<?php

// Le include de articles_dao_write.php est fait dans le controleur
// Enregistrement des modification de la barre de menu
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



    /* En cas de suppression d'un article dans la barre de menu,
     * il est inutile de le supprimer dans la liste des articles.
     * Il ne sera pas de toute façon enregistré.
     */
    case 'suppressionArticle':
        // Afin d'avoir un index sans trou, on recréé la liste d'article
        $menuTemp = array();
        foreach ($_SESSION['barre_menu'][$idMenu][1] as $id => $element) {
            if ($id != $idArticle) {
                $menuTemp [] = $element;
            }
        }
        if (count($menuTemp)==1){
            $ancienNom = $menuTemp [0];
            $nouveauNom = $_SESSION['barre_menu'][$idMenu][0];
            $menuTemp [0] = $_SESSION['barre_menu'][$idMenu][0];
        }
        $_SESSION['barre_menu'][$idMenu][1] = $menuTemp;

        // Suppression de l'article de la liste des liens
        foreach ($listeArticles as $id => $value) {
            if ($value == $nomArticle) {
                unset($_SESSION['listeLiens'][$id]);
            }
            if ($value == $ancienNom) {
                unset($_SESSION['listeLiens'][$id]);
                $_SESSION['listeLiens'][] = $nouveauNom;
            }
        }

        break;



    /* En cas de suppression d'un article dans la barre de menu,
     * il est inutile de le supprimer dans la liste des articles.
     * Il ne sera pas de toute façon enregistré.
     */
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
        /* Dans le cas où l'article est le premier du menu,
         * on le déplace dans le menu précédent,
         * si le menu n'est pas le premier.
         */ else {
            if ($idMenu > 0) {
                $_SESSION['barre_menu'][$idMenu - 1][1][] = $_SESSION['barre_menu'][$idMenu][1][$idArticle];
                $nombreArticlesTemp = count($_SESSION['barre_menu'][$idMenu][1]);
                $menuTemp = array();
                for ($i = 1; $i < $nombreArticlesTemp; $i++) {
                    $menuTemp[] = $_SESSION['barre_menu'][$idMenu][1][$i];
                }
                $_SESSION['barre_menu'][$idMenu][1] = $menuTemp;
                unset($menuTemp);
            }
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
        /* Dans le cas où l'article est le dernier du menu,
         * on le déplace dans le menu suivant,
         * si le menu n'est pas le dernier.
         */ else {
            if ($idMenu < (count($_SESSION['barre_menu']) - 1)) {
                $menuTemp = array();
                $menuTemp[] = $_SESSION['barre_menu'][$idMenu][1][$idArticle];
                unset($_SESSION['barre_menu'][$idMenu][1][$idArticle]);
                $nombreArticlesTempSuiv = count($_SESSION['barre_menu'][$idMenu + 1]);
                for ($i = 0; $i < $nombreArticlesTempSuiv; $i++) {
                    $menuTemp[] = $_SESSION['barre_menu'][$idMenu + 1][1][$i];
                }
                $_SESSION['barre_menu'][$idMenu + 1][1] = $menuTemp;
                unset($menuTemp);
            }
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
        // Décalage des articles présents ...
        $nombreArticlesDansMenu = count($_SESSION['barre_menu'][$idMenu][1]);
        for ($i = $nombreArticlesDansMenu; $i > $idArticle; $i--) {
            $_SESSION['barre_menu'][$idMenu][1][$i] = $_SESSION['barre_menu'][$idMenu][1][$i - 1];
        }
        // ... pour ajouter le nouvel article.
        $_SESSION['barre_menu'][$idMenu][1][$idArticle] = $modification;


        // Ajout d'un premier élément au nouvel article
        $_SESSION['articles'][$modification][] = array('Titre article', utf8_decode($modification));
        $_SESSION['articles'][$modification][] = array('Paragraphe', utf8_decode('Article en cours de rédaction'));

        // Ajout de l'article à la liste des liens
        $_SESSION['listeLiens'][] = $modification;
        break;




    case 'ajouterArticleAuDessous':
        // Décalage des articles présents ...
        $nombreArticlesDansMenu = count($_SESSION['barre_menu'][$idMenu][1]);
        for ($i = $nombreArticlesDansMenu; $i > $idArticle + 1; $i--) {
            $_SESSION['barre_menu'][$idMenu][1][$i] = $_SESSION['barre_menu'][$idMenu][1][$i - 1];
        }
        // ... pour ajouter le nouvel article.
        $_SESSION['barre_menu'][$idMenu][1][$idArticle + 1] = $modification;

        // Ajout d'un premier élément au nouvel article
        $_SESSION['articles'][$modification][] = array('Titre article', utf8_decode($modification));
        $_SESSION['articles'][$modification][] = array('Paragraphe', utf8_decode('Article en cours de rédaction'));

        // Ajout de l'article à la liste des liens
        $_SESSION['listeLiens'][] = $modification;
        break;




    case 'ajouterMenuAuDessus':
        // Décalage des menus présents ...
        $nombreMenusDansBarreMenu = count($_SESSION['barre_menu']);
        for ($i = $nombreMenusDansBarreMenu; $i > $idMenu + 1; $i--) {
            $_SESSION['barre_menu'][$i] = $_SESSION['barre_menu'][$i - 1];
        }
        // ... pour ajouter le nouveau menu.
        $_SESSION['barre_menu'][$idMenu] = array($modification, array($modification));

        // Ajout d'un premier article au nouveau menu
        // Ajout d'un premier élément au nouvel article du nouveau menu
        $_SESSION['articles'][$modification][] = array('Titre article', utf8_decode($modification));
        $_SESSION['articles'][$modification][] = array('Paragraphe', utf8_decode('Article en cours de rédaction'));

        // Ajout de l'article à la liste des liens
        $_SESSION['listeLiens'][] = $modification;
        break;




    case 'ajouterMenuAuDessous':
        // Décalage des menus présents ...
        $nombreMenusDansBarreMenu = count($_SESSION['barre_menu']);
        for ($i = $nombreMenusDansBarreMenu; $i > $idMenu + 1; $i--) {
            $_SESSION['barre_menu'][$i] = $_SESSION['barre_menu'][$i - 1];
        }
        // ... pour ajouter le nouveau menu.
        $_SESSION['barre_menu'][$idMenu] = array($modification, array($modification));

        // Ajout d'un premier élément au nouvel article du nouveau menu
        $_SESSION['articles'][$modification][] = array('Titre article', utf8_decode($modification));
        $_SESSION['articles'][$modification][] = array('Paragraphe', utf8_decode('Article en cours de rédaction'));

        // Ajout de l'article à la liste des liens
        $_SESSION['listeLiens'][] = $modification;
        break;
}
// Enregistrement
saveXml($_SESSION['articles'], $_SESSION['barre_menu']);
// Réaffichage de la page afin de forcer la mise à jour des objets et de l'affichage
header('Location: ../admin/index.php');




