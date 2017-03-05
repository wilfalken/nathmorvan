<?php 
// Définition des types de balises
$balisesArticles = array (
		'Titre article' => array (
				'<h1>',
				'</h1>'
		),
		'Sous-titre' => array (
				'<h2>',
				'</h2>'
		),
		'Paragraphe' => array (
				'<p>',
				'</p>'
		),
		'Image' => array (
				'<img src="../donnees/images/',
				'">'
		),
		'Lien' => array (
				'<a href="',
				'">',
				'</a>'
		),
    		'Fichier' => array (
				'<a href="../donnees/fichiers/',
				'">',
				'</a>'
		),
                'temp' => '<span><br><select name=type>'
                    .'<option>Sous-titre</option>'
                    .'<option>Paragraphe</option>'
                    .'<option>Image</option>'
                    .'<option>Lien</option>'
                    .'<option>Fichier</option>'
                    .'<option>Carrousel</option>'
                .'</select><br><br>'
                    .'<a class=bouton name=bouton_valider_insertion href="">Valider le type</a>'
                    .'<a class=bouton name=bouton_supprimer href="">Annuler</a></span>',
    
                'Carrousel' => getCarrousel()	
);

function getCarrousel(){
    $carrousel =  '<span>';
// stefan : Définition du répertoire à parcourir. 
$cssDirectory = "../donnees/images/carrousel";
    // stefan : Si on arrive à ouvrir le répertoire ...
    if ($dossier = opendir ($cssDirectory)) {
        // stefan : ..., tant qu'il y a un fichier qui n'a pas été lu ...
        while ( false !== ($file = readdir ( $dossier )) ) {
            /* stefan : ... on vérifie qu'il a bien "css" en extension (les caractères après le ".".
             * explode() permet de séparer une string en un tableau à chaque "." rencontré.
             */
            $fileExtension = explode(".", $file)[count (explode(".", $file))-1];
            if ($fileExtension == "jpg") {
                /* stefan : Si le nom de fichier fini bien par "css",
                 * on créé le lien html vers css.
                 */
                $carrousel .= '<img src="../donnees/images/carrousel/'.$file.'">';
                }   
            }
        closedir ( $dossier );
    }
    $carrousel .= '</span>';
    return $carrousel;
}



?>