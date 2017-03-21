<?php


echo '<h1>Gestion générale des articles</h1>';


echo '<br><br>';

// Création de la liste des noms de menus et articles déjà utilisés dans $_SESSION
$listeNomsMenusArticles = array_merge(array_keys($_SESSION['articles']), array_keys($_SESSION['barre_menu']));
$nomsMenusArticles = '';
foreach ($listeNomsMenusArticles as $nom) {
    $nomsMenusArticles .= $nom . "#";
}

$liste = '<ul id=gestionArticles >';
$idMenu = 0;
$idArticle = 0;
foreach ($_SESSION['barre_menu'] as $idMenu => $menu) {
    $nomMenu = $menu[0];
    $nombreMenus = count($_SESSION['barre_menu']);
    // S'il s'agit d'un lien direct, on l'ajoute
    if (count($menu[1]) == 1) {
        $article = $menu[0];
        $nombreArticles = 1;
        $liste .= '<li><span class=nom data-idMenu=' . $idMenu . ' data-nomMenu="' . utf8_encode($nomMenu) . '" data-idArticle=0 data-nomArticle="' . utf8_encode($article) . '" data-nombreMenusMaximum=' . $nombreMenusMaximum . '  data-nombreArticlesMaximum=' . $nombreArticlesMaximum . ' data-nombreMenus=' . $nombreMenus . '  data-nombreArticles=' . $nombreArticles . '><b>' . utf8_encode($article) . '</b></span>' .
                '<ul class=listeModification>' .
                '<li>' . boutonModificationNomMenu($nomsMenusArticles) . '</li>' .
                '<li>' . boutonSuppressionMenuLienDirect() . '</li>' .
                '<li>' . boutonAjouterArticleDansMenu($nomsMenusArticles) . '</li>' .
                '<li>' . boutonAjouterMenuAuDessus($nomsMenusArticles) . '</li>' .
                '<li>' . boutonAjouterMenuAuDessous($nomsMenusArticles) . '</li>' .
                '<li>' . boutonDeplacerMenuVersHaut() . '</li>' .
                '<li>' . boutonDeplacerMenuVersBas() . '</li>' .
                '</ul></li>';
    }
    // Sinon, il s'agit d'une liste de lien
    else {
        // On créé donc un menu ...
        $nombreArticles = count($menu[1]);
        $liste .= '<li><span class=nom data-idMenu=' . $idMenu . ' data-nomMenu="' . utf8_encode($nomMenu) . '" data-nomArticle="" data-nombreMenusMaximum=' . $nombreMenusMaximum . '  data-nombreArticlesMaximum=' . $nombreArticlesMaximum . ' data-nombreMenus=' . $nombreMenus . '  data-nombreArticles=' . $nombreArticles . '><b>' . utf8_encode($nomMenu) . '</b></span>' .
                '<ul class=listeModification>' .
                '<li>' . boutonModificationNomMenu($nomsMenusArticles) . '</li>' .
                '<li>' . boutonSuppressionMenu() . '</li>' .
                '<li>' . boutonAjouterMenuAuDessus($nomsMenusArticles) . '</li>' .
                '<li>' . boutonAjouterMenuAuDessous($nomsMenusArticles) . '</li>' .
                '<li>' . boutonDeplacerMenuVersHaut() . '</li>' .
                '<li>' . boutonDeplacerMenuVersBas() . '</li>' .
                '</ul></li>';
        // ... auquel on ajoute ses liens ...
        foreach ($menu[1] as $idArticle => $article) {
            $liste .= '<li><span class=nom data-idMenu=' . $idMenu . ' data-nomMenu="' . utf8_encode($nomMenu) . '" data-idArticle=' . $idArticle . ' data-nomArticle="' . utf8_encode($article) . '" data-nombreMenusMaximum=' . $nombreMenusMaximum . '  data-nombreArticlesMaximum=' . $nombreArticlesMaximum . ' data-nombreMenus=' . $nombreMenus . '  data-nombreArticles=' . $nombreArticles . '>' . utf8_encode($article) . '</span>' .
                    '<ul class=listeModification>' .
                    '<li>' . boutonModificationNomArticle($nomsMenusArticles) . '</li>' .
                    '<li>' . boutonSuppressionArticle() . '</li>' .
                    '<li>' . boutonAjouterArticleAuDessus($nomsMenusArticles) . '</li>' .
                    '<li>' . boutonAjouterArticleAuDessous($nomsMenusArticles) . '</li>' .
                    '<li>' . boutonDeplacerArticleVersHaut() . '</li>' .
                    '<li>' . boutonDeplacerArticleVersBas() . '</li>' .
                    '</ul></li>';
        }
    }$liste .= '<br>';
}
$liste .= '</ul><br><br><br><br>';

echo $liste;

function boutonAjouterMenuAuDessous($nomsMenusArticles) {
    return '<a class=bouton_gestion_article name=ajouterMenuAuDessous data-nomsMenusArticles="' . utf8_encode($nomsMenusArticles) . '" href="">Ajouter un menu au-dessous</a>';
}

function boutonAjouterMenuAuDessus($nomsMenusArticles) {
    return '<a class=bouton_gestion_article name=ajouterMenuAuDessus data-nomsMenusArticles="' . utf8_encode($nomsMenusArticles) . '" href="">Ajouter un menu au-dessus</a>';
}

function boutonAjouterArticleAuDessous($nomsMenusArticles) {
    return '<a class=bouton_gestion_article name=ajouterArticleAuDessous data-nomsMenusArticles="' . utf8_encode($nomsMenusArticles) . '" href="">Ajouter un article au-dessous</a>';
}

function boutonAjouterArticleDansMenu($nomsMenusArticles) {
    return '<a class=bouton_gestion_article name=ajouterArticleAuDessous data-nomsMenusArticles="' . utf8_encode($nomsMenusArticles) . '" href="">Ajouter un article</a>';
}

function boutonAjouterArticleAuDessus($nomsMenusArticles) {
    return '<a class=bouton_gestion_article name=ajouterArticleAuDessus data-nomsMenusArticles="' . utf8_encode($nomsMenusArticles) . '" href="">Ajouter un article au-dessus</a>';
}

function boutonDeplacerMenuVersBas() {
    return '<a class=bouton_gestion_article name=deplacerMenuVersBas href="">Déplacer le menu vers le bas</a>';
}

function boutonDeplacerMenuVersHaut() {
    return '<a class=bouton_gestion_article name=deplacerMenuVersHaut href="">Déplacer le menu vers le haut</a>';
}

function boutonDeplacerArticleVersBas() {
    return '<a class=bouton_gestion_article name=deplacerArticleVersBas href="">Déplacer l\'article vers le bas</a>';
}

function boutonDeplacerArticleVersHaut() {
    return '<a class=bouton_gestion_article name=deplacerArticleVersHaut href="">Déplacer l\'article vers le haut</a>';
}

function boutonSuppressionMenu() {
    return '<a class=bouton_gestion_article name=suppressionMenu href="">Supprimer le menu et ses articles</a>';
}

// C'est un doublon de celui juste au-dessus mais on change le texte affiché pour l'utilisateur
function boutonSuppressionMenuLienDirect() {
    return '<a class=bouton_gestion_article name=suppressionMenu href="">Supprimer le menu et son article</a>';
}

function boutonSuppressionArticle() {
    return '<a class=bouton_gestion_article name=suppressionArticle href="">Supprimer l\'article</a>';
}

function boutonModificationNomArticle($nomsMenusArticles) {
    return '<a class=bouton_gestion_article name=modificationNomArticle data-nomsMenusArticles="' . utf8_encode($nomsMenusArticles) . '" href="">Modifier le nom de l\'article</a>';
}

function boutonModificationNomMenu($nomsMenusArticles) {
    return '<a class=bouton_gestion_article name=modificationNomMenu data-nomsMenusArticles="' . utf8_encode($nomsMenusArticles) . '" href="">Modifier le nom du menu</a>';
}

?>