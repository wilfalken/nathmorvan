<?php
Function afficher_barre_menu($barre_menu) {


// Ouverture de la balise de liste
$barre_menu_affichee = '<ul class="titres">';

foreach ($barre_menu as $index => $menu){
	// S'il s'agit d'un lien direct, on l'ajoute
	if (gettype($menu)=='string'){
	$lien = $menu;
	$barre_menu_affichee .= '<li><a href="index.php?article_a_afficher='.utf8_encode ($lien).'">'.utf8_encode ($lien).'</a></li>';
	}
	// Sinon, il s'agit d'une liste de lien
	else if (gettype($menu)=='array'){
		// On créé donc un menu ...
		$barre_menu_affichee .= '<li>'.utf8_encode ($index).'<ul class="lignes">';
		// ... auquel on ajoute ses liens ...
		foreach ($menu as $lien){
			$barre_menu_affichee .= '<li><a href="index.php?article_a_afficher='.utf8_encode ($lien).'">'.utf8_encode ($lien).'</a></li>';
		}
		// ... puis on ajoute la balise de fermeture du menu
		$barre_menu_affichee .= '</ul></li>';
	}
}

// Ajout d'éléments en cas de mode administrateur
if ($_SESSION['dolto']=='admin'){
    // Pages de gestion
    $barre_menu_affichee .='<li><a href="index.php?article_a_afficher=gestionArticles">Gestion articles</a></li>';
    $barre_menu_affichee .='<li><a href="index.php?article_a_afficher=gestionSlideShow">Gestion carrousel</a></li>';
    // Guides en format PDF
    $barre_menu_affichee .='<li><a href="../donnees/guides/guide_administrateur.pdf" target="_blank">Guide de l\'administrateur</a></li>';
    $barre_menu_affichee .='<li><a href="../donnees/guides/guide_developpeur.pdf" target="_blank">Guide du développeur</a></li>';
}

// Fermeture de la balise de liste
$barre_menu_affichee .= '</ul>';

// Affichage de la barre de menu
echo $barre_menu_affichee;
}
?>
