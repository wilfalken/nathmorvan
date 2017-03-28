<?php
// Définition du répertoire à parcourir.
$jsDirectory = "../js/base";
    // Si on arrive à ouvrir le répertoire ...
    if ($dossier = opendir ($jsDirectory)) {
        // ..., tant qu'il y a un fichier qui n'a pas été lu ...
        while ( false !== ($file = readdir ( $dossier )) ) {
            /* ... on vérifie qu'il a bien "js" en extension (les caractères après le ".".
             * explode() permet de séparer une string en un tableau à chaque "." rencontré.
             */
            $fileExtension = explode(".", $file)[count (explode(".", $file))-1];
            if ($fileExtension == "js") {
                /* Si le nom de fichier fini bien par "js",
                 * on créé le lien html vers js.
                 */
                echo '<script src="'.$jsDirectory.'/'.$file.'"></script>';
                }   
            }
        closedir ( $dossier );
    }
    
    ?>
