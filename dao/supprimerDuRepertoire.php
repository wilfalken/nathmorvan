<?php

if (!empty($_POST['fonction']) && $_POST['fonction']=='supprimerDuRepertoire'){
    // Fonction suppression
    unlink (utf8_decode($_POST['nomRepertoire']).'/'.utf8_decode($_POST['nomFichier']));
    unset ($_POST['fonction']);
    unset ($_POST['nomFichier']);
    unset ($_POST['nomRepertoire']);
}
