<?php

/* Utiliser une fonction n'est pas nécessaire ici
 * mais cela permet d'unifier visuellement l'index
 */

Function afficher_footer() {
    $espace = '&nbsp;';
    $trait = '&#8209;';
    echo '
	Ecole' . $espace . 'maternelle' . $espace . 'Françoise' . $espace . 'Dolto - 5' . $espace . 'bis' . $espace . 'rue' . $espace . 'des' . $espace . 'Ecoles' . $espace . 'à' . $espace . 'Sené' . $espace . '(56860) - 02' . $espace . '97' . $espace . '66' . $espace . '51' . $espace . '52 - 
	<a href="mailto:maternelle-dolto@sene.com">maternelle'.$trait.'dolto@sene.com</a>';
}

?>