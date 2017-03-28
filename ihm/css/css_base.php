<?php
// Définition du répertoire à parcourir. 
$cssDirectory = "../ihm/css/base";
    // Si on arrive à ouvrir le répertoire ...
    if ($dossier = opendir ($cssDirectory)) {
        // ..., tant qu'il y a un fichier qui n'a pas été lu ...
        while ( false !== ($file = readdir ( $dossier )) ) {
            /* ... on vérifie qu'il a bien "css" en extension (les caractères après le ".".
             * explode() permet de séparer une string en un tableau à chaque "." rencontré.
             */
            $fileExtension = explode(".", $file)[count (explode(".", $file))-1];
            if ($fileExtension == "css") {
                /* Si le nom de fichier fini bien par "css",
                 * on créé le lien html vers css.
                 */
                echo '<link rel="stylesheet" type="text/css" href="'.$cssDirectory.'/'.$file.'">';
                }   
            }
        closedir ( $dossier );
    }
    
    ?>