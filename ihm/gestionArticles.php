<?php

echo '<h1>Gestion générale des articles</h1>';	


$lien_modif = '<a class=bouton_modifier_nom_article href="">Modifier le nom de l\'article</a>';
$liste = '';
foreach ($_SESSION['barre_menu'] as $index => $menu){
	// S'il s'agit d'un lien direct, on l'ajoute
	if (gettype($menu)=='string'){
	$lien = $menu;
	$liste .= '<span>'.utf8_encode($lien).'</span>'.$lien_modif.'<br>';
	}
	// Sinon, il s'agit d'une liste de lien
	else if (gettype($menu)=='array'){
		// On créé donc un menu ...
		$liste .= utf8_encode($index).$lien_modif.'<br>';
		// ... auquel on ajoute ses liens ...
		foreach ($menu as $lien){
			$liste .= '- '.'<span>'.utf8_encode($lien).'</span>'.$lien_modif.'<br>';
		}
	}
	$liste .= '<br>';
}

echo $liste;
?>