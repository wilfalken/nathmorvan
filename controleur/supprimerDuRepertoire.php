<?php

// Fonction suppression
unlink (utf8_decode($_POST['nomRepertoire']).'/'.utf8_decode($_POST['nomFichier']));
unset ($_POST['fonction']);
unset ($_POST['nomFichier']);
unset ($_POST['nomRepertoire']);

