<?php

if (!empty($_POST['supprimerImage'])){
    $fonction = $_POST['fonction'];
    $nomImageASupprimer = utf8_decode($_POST['nomImageASupprimer']);
    unset ($_POST['fonction']);
    unset ($_POST['nomImageASupprimer']);
    $fonction($nomImageASupprimer);
}

function supprimerImageCarrousel($nomImageASupprimer){
    unlink('../donnees/images/carrousel/'.$nomImageASupprimer);
}

