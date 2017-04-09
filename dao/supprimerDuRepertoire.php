<?php

// Fonction suppression
unlink (utf8_decode($_POST['nomRepertoire']).'/'.utf8_decode($_POST['nomFichier']));
// Si le fichier est dans le répertoire image, on supprime aussi la vignette
if (strpos(utf8_decode($_POST['nomRepertoire']),"images")!=-1){
    unlink (utf8_decode($_POST['nomRepertoire']).'/vignettes/'.utf8_decode($_POST['nomFichier']));
}
unset ($_POST['fonction']);
unset ($_POST['nomFichier']);
unset ($_POST['nomRepertoire']);

