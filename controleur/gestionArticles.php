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
            $_SESSION['barre_menu'][$idMenu][1] = $menuTemp;

            // Suppression de l'article de la liste des liens
            foreach ($listeArticles as $id => $value) {
                if ($value == $nomArticle) {
                    unset($_SESSION['listeLiens'][$id]);
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




