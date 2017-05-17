<?php

Function afficher_barre_menu($barre_menu) {


// Ouverture de la balise de liste
    $barre_menu_affichee = '<div id=titre><span style="color:#ff9600;">E</span>cole <span style="color:#63c000;">F</span>rançoise <span style="color:#63c000;">D</span>olto</div>'
            . '<div  id="fondMenu"></div>'
            . '<div  id="ombreMenu"><img src="../ihm/img/ombreMenu.png"></div>'
            . '<div  id="degradeMenu"><img src="../ihm/img/degradeMenu.png"></div>'
            . '<div  id="imageMenu"><img src="../ihm/img/imageMenu.png"></div>'
            . '<div  id="logoMenu"><a href=""><img src="../ihm/img/logoMenu.gif"></a></div>'
            . '<div  id="menu">'
            . '<ul class="titres">';
    foreach ($barre_menu as $menu) {
        // S'il s'agit d'un lien direct, on l'ajoute
        if (count($menu[1]) == 1) {
            $lien = $menu[0];
            $barre_menu_affichee .= '<li><a class="lienDirect" href="index.php?article_a_afficher=' . ($lien) . '"><div class="outerBox"><div class="innerBox">' . ($lien) . '</div></div></a></li>';
        }

        // Sinon, il s'agit d'une liste de lien
        else {
            // On créé donc un menu ...
            $barre_menu_affichee .= '<li><div class="outerBox"><div class="innerBox">' . ($menu[0]) . '</div></div><ul class="lignes">';
            // ... auquel on ajoute ses liens ...
            foreach ($menu[1] as $id => $lien) {
                $barre_menu_affichee .= '<li><a href="index.php?article_a_afficher=' . ($lien) . '">' . ($lien) . '</a></li>';
            }
            // ... puis on ajoute la balise de fermeture du menu
            $barre_menu_affichee .= '</ul></li>';
        }
    }
// Ajout d'éléments en cas de mode administrateur
    if ($_SESSION['dolto'] == 'admin') {
        $barre_menu_affichee .= '<li><div class="outerBox"><div class="innerBox">Menu administrateur</div></div><ul class="lignes">';
        // Pages de gestion
        $barre_menu_affichee .= '<li><a href="index.php?article_a_afficher=gestionArticles">Gestion des articles</a></li>';
        $barre_menu_affichee .= '<li><a href="index.php?article_a_afficher=gestionSlideShow">Gestion du carrousel</a></li>';
        $barre_menu_affichee .= '<li><a href="index.php?article_a_afficher=gestionImages">Gestion des images</a></li>';
        $barre_menu_affichee .= '<li><a href="index.php?article_a_afficher=gestionFichiers">Gestion des fichiers</a></li>';
        // Guides en format PDF
        $barre_menu_affichee .= '<li><a href="../donnees/guides/guide_administrateur.pdf" target="_blank">Guide de l\'administrateur</a></li>';
        //$barre_menu_affichee .='<li><a href="../donnees/guides/guide_developpeur.pdf" target="_blank">Guide du développeur</a></li>';
        $barre_menu_affichee .= '</ul></li>';
    }

// Fermeture de la balise de liste
    $barre_menu_affichee .= '</ul></div>';

// Affichage de la barre de menu
    echo $barre_menu_affichee;
}

?>
