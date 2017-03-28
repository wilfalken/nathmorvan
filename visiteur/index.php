<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title>Ecole maternelle Dolto</title>
<?php header('Content-type: text/html; charset=utf-8');?>

<?php
// Liens vers les css
include '../ihm/css/css_base.php';
// Gestion de l'icône
//include '../ihm/icon/icon.php';
// Include de la mise en forme des articles en balises html
include '../ihm/affichageArticles.php';
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
// On déclare une session visiteur
$_SESSION['dolto']='visiteur';


?>
</head>


<body>


<!--  Div de contenu, le contenu du div est modifié en fonction des liens cliqués -->
<div id="contenu">
<?php

/* $_GET permet de récupérer l'url affichée en clair dans la barre
 * Par défaut, cette zone est vide, on va donc afficher l'accueil.php.
 * On ne peut tester si $_GET est vide avec =='' ou ==null,
 * il faut utiliser empty() ou isset()
 * On gère aussi le cas d'erreur quand une adresse est forcée dans la barre de navigation
 * en vérifiant que le lien utilisé est bien dans la liste désérializée.
 * La valeur par défaut est le premier article (fonction reset()).
 */
if (empty($_GET['article_a_afficher']) || !(in_array($_GET['article_a_afficher'], $_SESSION['listeLiens']))){
	 afficher_article(reset($_SESSION['articles']));
}

/* Si l'espace apràs index.php? n'est pas vide,
 * alors on se sert de ce qu'il y a marqué pour remplir
 * la div "contenu"
 */
else {
    afficher_article($_SESSION['articles'][$_GET['article_a_afficher']]);
}

?>
</div>

<!--
le navigateur affiche les éléments en les empilant, le premier déclaré sera celui dessous
les autres seront ajoutés dessus, c'est pourquoi il faut commencer par déclarer le contenu
-->

<!--  le header est divisé en trois :
- header pour la couleur de fond
- image-header pour l'image
- menu pour le menu
 -->

<!--  couleur sous Header -->
<!--
<div id="header"></div>
-->

<!--  Image sous le menu -->
<!--
<div id="image_header"><img src="../ihm/img/header.png"></div>
-->

<!--  menu du Header -->
<div><?php afficher_barre_menu($_SESSION['barre_menu']);?></div>

<!--  Footer -->
<div id="footer"><?php afficher_footer();?>	</div>


<!-- liens jQuery et scripts spécifiques au site -->
<?php include '../js/js_base.php'; ?>

</body>
</html>