<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title>Ecole maternelle Dolto</title>
<?php header('Content-type: text/html; charset=utf-8');?>
<?php
// Voir plus bas pour la gestion de la session
session_start();

// Liens vers les css
include '../ihm/css/css_base.php';
// Liens vers les css admin complétant ou écrasant les autres
include '../ihm/css/css_admin.php';
//Gestion de l'icône
//include '../ihm/icon/icon.php';
//// Include de toutes les pages
include '../dao/articles_dao_read.php';
// Include de la mise en forme des articles en balises html
include '../ihm/affichageModificationArticles.php';
// Include de la mise en forme de la barre de menu en balises html
include "../ihm/affichageBarre_menu.php";
// Include de la mise en forme du footer en balises html
include "../ihm/affichageFooter.php";
// Include des paramètres de configuration
include '../donnees/config.php';
// Include pour test
//include '../dao/save.php';

// Include du controleur si une fonction a été appelée depuis Javascript
if (!empty($_POST['fonction'])){
    include '../controleur/controleur.php';
}
// Include du controleur d'upload si une tentive de chargement a été faite
if (!empty($_FILES)){
    include '../controleur/controleurUpLoad.php';
}

/* Création des variables servant par la suite lors du premier accès au site.
 * Ces dernières sont stockées dans $_SESSION afin de pouvoir être rappelées
 * lors du rechargement de la page d'index.
 */
if (empty($_SESSION['articles'])&& empty($_SESSION['barre_menu'])&& empty($_SESSION['listeLiens'])){
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

// Affichage des fenêtres modales de résultats d'upload de fichier ou d'image
include '../ihm/affichageResultatUpLoadModale.php';


//@print_r ($_SESSION['listeLiens']);
//echo "<br><br>";
//@print_r ($_SESSION['barre_menu']);
//echo "<br><br>";
//@print_r($_SESSION['articles'][$_GET['article_a_afficher']]);
//echo "<br><br>";
//echo @$_SESSION['articles'][$_GET['article_a_afficher']][0][1];
/* $_GET permet de récupérer l'url affichée en clair dans la barre
 * Par défaut, cette zone est vide, on va donc afficher l'accueil.php.
 * On ne peut tester si $_GET est vide avec =='' ou ==null,
 * il faut utiliser empty() ou isset()
 */

// Définition des ihm relatives à la gestion, ils sont donc autorisés en accès
$listeArticlesGestion = array ('gestionArticles','gestionSlideShow','gestionImages','gestionFichiers');

// Soit la variable est vide et on affiche le premier article ...
if (empty($_GET['article_a_afficher'])){
	afficher_modifier_article(reset($_SESSION['articles']));
}
// .. soit la page à afficher fait partie de la liste des articles relatifs à la gestion du site ...
else if (in_array($_GET['article_a_afficher'],$listeArticlesGestion)){
	include ('../ihm/'.$_GET['article_a_afficher'].'.php');
}
// ... soit en cas d'erreur ou de variable , on affiche le premier article
else if (!(in_array($_GET['article_a_afficher'], $_SESSION['listeLiens']))){
	afficher_modifier_article(reset($_SESSION['articles']));
}

// ... soit la page demandée (affichée après le ?)
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
<!--
<div id="header"></div>
-->


<!--
Il ne faut pas mettre l'image sous le menu
cela bloque la sélection des éléments en mode admin sur toute la partie
de la page où se trouve l'image
-->
<!--  <div id="image_header"><img src="../ihm/img/header.png"></div> -->


<!--  menu du Header -->
<div><?php afficher_barre_menu($_SESSION['barre_menu']);?></div>

<!--  Footer -->
<div id="footer"><?php afficher_footer();?></div>


<!-- liens jQuery et scripts spécifiques au site -->
<?php include '../js/js_base.php'; include '../js/js_admin.php'; ?>


</body>
</html>