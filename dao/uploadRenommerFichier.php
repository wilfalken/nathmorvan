<?php

function renommerFichier($nomFichier, $repertoire) {
    // Récupération de la liste des fichiers présents
    if ($dossier = opendir($repertoire)) {
        while (false !== ($file = readdir($dossier))) {
            $fileExtension = explode(".", $file)[count(explode(".", $file)) - 1];
            // Permet de supprimer les répertoires et "." et ".."
            if ($fileExtension != "" && (strpos($file,"."))) {
                $listeFichiersExistant[] = $file;
                //echo $file."<br>";
            }
        }
        closedir($dossier);
    }
    // Déclaration de liste alphanumérique
    $alphaNum = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];
    // Déclaration du nombre d'essais pour test
    //$nombreEssais = 0;
    do {
        $code = "";
        for ($i = 0; $i < 4; $i++) {
            $code .= $alphaNum[rand(0, count($alphaNum) - 1)];
        }
        $nouveauNomFichier = explode(".", $nomFichier)[0] . "##" . $code . "." . explode(".", $nomFichier)[1];
        $nombreEssais ++;
    } while (in_array($nouveauNomFichier, $listeFichiersExistant));
    //echo $nombreEssais . "<br>";
    return $nouveauNomFichier;
}