<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title>Ecole maternelle Dolto</title>
<?php header('Content-type: text/html; charset=utf-8');?>
<?php

// Liens vers les css
include '../ihm/css/css_base.php';
// Liens vers les css admin complétant ou écrasant les autres
include '../ihm/css/css_admin.php';
//Gestion de l'icône
//include '../ihm/icon/icon.php';
// Include de la mise en forme des articles en balises html
include '../ihm/affichageModificationArticles.php';
// Include de toutes les pages
include '../dao/articles_dao_read.php';
// Include de la mise en forme de la barre de menu en balises html
include "../ihm/affichageBarre_menu.php";
// Include de la mise en forme du footer en balises html
include "../ihm/affichageFooter.php";
/* Création des variables servant par la suite lors du premier accès au site.
 * Ces dernières sont stockées dans $_SESSION afin de pouvoir être rappelées
 * lors du rechargement de la page d'index.
 */
session_start();
if (empty($_SESSION['articles'])&&empty($_SESSION['barre_menu'])&& empty($_SESSION['listeLiens'])){
    getXml();
}
// On déclare aussi une session admin afin de gérer l'affichage de la barre de menu
$_SESSION['dolto']='admin';
?>
</head>


<body>



<!--  Div de contenu, le contenu du div est modifié en fonction des liens cliqués -->
<div id="contenu">
<?php
//print_r($_SESSION['articles'][$_GET['article_a_afficher']]);
/* $_GET permet de récupérer l'url affichée en clair dans la barre
 * Par défaut, cette zone est vide, on va donc afficher l'accueil.php.
 * On ne peut tester si $_GET est vide avec =='' ou ==null,
 * il faut utiliser empty() ou isset()
 */

// Soit la variable est vide ...
if (empty($_GET['article_a_afficher'])){
	afficher_modifier_article($_SESSION['articles']['Accueil']);
}
// .. soit la page à afficher est la gestion d'article ...
else if ($_GET['article_a_afficher']=='gestionArticles'){
	include ('../ihm/gestionArticles.php');
}
// .. soit la page à afficher est la gestion d'article ...
else if ($_GET['article_a_afficher']=='gestionSlideShow'){
	include ('../ihm/gestionSlideShow.php');
}
// ... soit en cas d'erreur ou de variable , on affiche l'accueil
else if (!(in_array($_GET['article_a_afficher'], $_SESSION['listeLiens']))){
	afficher_modifier_article($_SESSION['articles']['Accueil']);
}

// ... soit la page demand�e (affichée après le ?)
else {
	$article_a_afficher = $_GET['article_a_afficher'];
	afficher_modifier_article($_SESSION['articles'][$article_a_afficher]);
}

?>
</div>

<!--
le navigateur affiche les éléments en les empilant, le premier déclaré sera celui dessous
les autres seront ajoutés dessus, c'est pourquoi il faut commencer par déclarer le contenu
-->


<!--  couleur sous Header -->
<div id="header"></div>



<!--
Il ne faut pas mettre l'image sous le menu
cela bloque la sélection des éléments en mode admin sur toute la partie
de la page où se trouve l'image
-->
<!--  <div id="image_header"><img src="../ihm/img/header.png"></div> -->


<!--  menu du Header -->
<div id="menu"><?php afficher_barre_menu($_SESSION['barre_menu']);?></div>

<!--  Footer -->
<div id="footer"><?php afficher_footer();?></div>


<!-- liens jQuery et script spécifiques au site -->
<script src="../js/jquery-3.1.1.js"></script>
<script src="../js/gestionArticles.js"></script>
<script src="../js/gestionSlideShow.js"></script>
<script src="../js/modificationArticles.js"></script>
<script src="../js/slideShow.js"></script>


</body>
</html>