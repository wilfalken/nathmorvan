<?php

// Le include de articles_dao_write.php est fait dans le controleur

// Enregistrement des modification d'un élément d'un article

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
        // Réaffichage de la page afin de forcer la mise à jour des objets et de l'affichage
    header('Location: ../admin/index.php');



