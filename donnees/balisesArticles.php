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
        '"><img src="../donnees/images/vignettes/',
        '"></a>'
    ),
    'Lien' => array(
        '<a target="_blank" href="',
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
    . '<option>Google Map</option>'
    . '</select><br><br>'
    . '<a class=bouton name=bouton_valider_insertion href="">Valider le type</a>'
    . '<a class=bouton name=bouton_supprimer href="">Annuler</a></span>',
    'Carrousel' => getCarrousel(),
    'GoogleMap' => getGoogleMap()
);

// Les images du carrousel proviennent du répertoire ../donnees/images/carrousel/
function getCarrousel() {
    $carrousel = '<div class="slideshow"><ul>';
    $carrouselDirectory = "../donnees/images/carrousel";
    if ($dossier = opendir($carrouselDirectory)) {
        while (false !== ($file = readdir($dossier))) {
            $fileExtension = explode(".", $file)[count(explode(".", $file)) - 1];
            if ($fileExtension != "" && (strpos($file, "."))) {
                $carrousel .= '<li><a target="_blank" href="../donnees/images/carrousel/' . $file . '"><img src="../donnees/images/carrousel/vignettes/' . $file . '"></a></li>';
            }
        }
        closedir($dossier);
    }
    $carrousel .= '</ul></div><br><br>';
    return $carrousel;
}

function getGoogleMap() {
    return '
        <div class="googleMap">
    <iframe
  src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBjdWPUzSbZcHjfbW4bITpb9AxGAwFYbxM 
    &q=5+Rue+des+%C3%89coles,+56860+S%C3%A9n%C3%A9">
</iframe>
</div>';
}

?>