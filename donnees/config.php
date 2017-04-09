<?php
/* Ces valeurs sont envoyés côté client par gestionArticles.php,
 * puis utilisé par gestionArticles.js (appelé dans l'index)
 */


/* Il s'agit du nombre de menus que peut créer l'administrateur en plus du menu "Menu administrateur".
 * 0 ou négatif pour illimité.
 * Si la valeur ci-dessous est inférieure au nombre de menus existant,
 * on ne pourra ajouter un autre menu.
 * Par exemple, la limite est 6 et il y a 8 menus.
 * La limite effective est donc 8.
 * On ne peut en ajouter un 9e.
 * Si on supprime le 8e, la limite effective deviend 7.
 * On ne peut en rajouter un 8e.
 */
$nombreMenusMaximum = 6;

/* Il s'agit du nombre d'articles que peut contenir un menu.
 * 0 ou négatif pour illimité.
 * Même remarque pour la notion de limite effective.
 */
$nombreArticlesMaximum = 6;

/* Il s'agit de la taile des images du carrousel
 * une fois réduites et recadrées (16/9)
 */
$dimensionsImagesReduitesSlideshow = [640,360];

/* Il s'agit de la taile maximale des images du contenu
 * une fois réduites.
 * En cas de modification, il faut également modifier le slideshow.css.
 */
$dimensionsImagesReduites = [640,640];