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
                .'</select><br><br>'
                    .'<a class=bouton name=bouton_valider_insertion href="">Valider le type</a>'
                    .'<a class=bouton name=bouton_supprimer href="">Annuler</a></span>'
		
);

?>