<?php


// Définition des types de balises
$balisesArticles = array(
    'Titre article' => array(
        '<h1>',
        '</h1>'
    ),
    'Sous-titre' => array(
        '<h2>',
        '</h2>'
    ),
    'Paragraphe' => array(
        '<p>',
        '</p>'
    ),
    'Image' => array(
        '<a target="_blank" href="../donnees/images/',
        '"><img src="../donnees/images/',
        '"></a>'
    ),
    'Lien' => array(
        '<a href="',
        '">',
        '</a>'
    ),
    'Fichier' => array(
        '<a target="_blank" href="../donnees/fichiers/',
        '">',
        '</a>'
    ),
    'temp' => '<span><br><select name=type>'
    . '<option>Sous-titre</option>'
    . '<option>Paragraphe</option>'
    . '<option>Image</option>'
    . '<option>Lien</option>'
    . '<option>Fichier</option>'
    . '<option>Carrousel</option>'
    . '</select><br><br>'
    . '<a class=bouton name=bouton_valider_insertion href="">Valider le type</a>'
    . '<a class=bouton name=bouton_supprimer href="">Annuler</a></span>',
    'Carrousel' => getCarrousel()
);

// Les images du carrousel proviennent du répertoire ../donnees/images/carrousel/
function getCarrousel() {
    $carrousel = '<div class="slideshow"><ul>';
    $carrouselDirectory = "../donnees/images/carrousel";
    if ($dossier = opendir($carrouselDirectory)) {
        while (false !== ($file = readdir($dossier))) {
            $fileExtension = explode(".", $file)[count(explode(".", $file)) - 1];
            if ($fileExtension != "") {
                $carrousel .= '<li><a target="_blank" href="../donnees/images/carrousel/' . $file . '"><img src="../donnees/images/carrousel/' . $file . '"></a></li>';
            }
        }
        closedir($dossier);
    }
    $carrousel .= '</ul></div><br><br>';
    return $carrousel;
}

?>