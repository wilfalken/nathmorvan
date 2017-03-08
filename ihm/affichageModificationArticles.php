<?php
Function afficher_modifier_article($article) {
    include_once ('../donnees/balisesArticles.php');

    // Affichage de l'article mis en forme selon ses balises.
    foreach ( $article as $id =>$element ) {
        $classeModification = "";
        if ($element [0] == 'temp'){
            $bloc = '<div class="element modification" data-numero='.$id.'>'.$balisesArticles [$element [0]].'</div>';
        }
        else {
        // Gestion des liens et fichiers qui comportent trois balises
        if (($element [0] == 'Fichier') || ($element [0] == 'Lien')) {
            // Récupération des balises d'ouverture, centrale et de fermeture
            $in = $balisesArticles [$element [0]] [0];
            $mid = $balisesArticles [$element [0]] [1];
            $out = $balisesArticles [$element [0]] [2];
            // Affichage de chaque élément
            $bloc = $in.$element[1].$mid.$element[1].$out.'<br>';
        }
        // Gestion du carrousel qui est un ensemble de balise
        else if ($element [0] == 'Carrousel'){
            $bloc = $balisesArticles [$element [0]];
        }
        // ... pour tous les autres, deux balises suffisent
        else {
            // Récupération des balises d'ouverture et de fermeture
            $in = $balisesArticles [$element [0]] [0];
            $out = $balisesArticles [$element [0]] [1];
            // Affichage de chaque élément
            $bloc = $in.$element [1].$out;
        }
        /* Si ce n'est pas un Titre article (h1),
         * on met le bloc dans un span et on ajoute
         * les boutons de modification.
         * On ne traite pas le titre de l'article,
         * il est modifiable par la gestion du nombre d'article.
         */
        if ($element [0] != 'Titre article'){
            $bloc = '<div class="element" data-numero='.$id.' data-balise='.$element [0].'><span class=texteAffiche>'.utf8_encode($bloc).'</span>';
            // Gestion d'un espace supplémentaire pour les liens
            if ($element [0] == 'Lien'){
            $bloc .='<br><br>';
            }
            /* Ici commence la zone permettant les modifications.
             * Par défaut, il s'agit de texte, on va donc masquer le texte affiché
             * et affiché une textarea permettant la modification.
             * S'il s'agit de fichier à remplacer (type image ou texte),
             * la zone de modification sera un formulaire permettant l'upload.
             */
            $bloc .='<span class=texteModifiable>';
            if ($element [0] == 'Image' ){
                $bloc .= '<br>';
                $bloc .= '<form action="" method="post" enctype="multipart/form-data">';
                $bloc .= "Choisisez l'image à charger : ";
                $bloc .= ' <input type="file" name="uploadImage">';
                // On cache dans le formulaire l'id de l'élément modifié
                $bloc .= ' <input class="valeurCachee" type="text" name="idImage" value="'.$id.'">';
                // Ainsi que le nom de l'article
                $bloc .= ' <input class="valeurCachee" type="text" name="nomArticle" value="'.$article[0][1].'">';
                $bloc .= '<input type="submit" value="Modifier l\'image" name="submit">';
                $bloc .= '</form>';
                $bloc .= '<a class=bouton name=bouton_annuler_modification href="">Annuler</a>';
            }
            else if ($element [0] == 'Fichier' ){
                $bloc .= '<br>';
                $bloc .= '<form action="" method="post" enctype="multipart/form-data">';
                $bloc .= "Choisisez le fichier à charger : ";
                $bloc .= ' <input type="file" name="uploadFichier">';
                // On cache dans le formulaire l'id de l'élément modifié
                $bloc .= ' <input class="valeurCachee" type="text" name="idFichier" value="'.$id.'">';
                // Ainsi que le nom de l'article
                $bloc .= ' <input class="valeurCachee" type="text" name="nomArticle" value="'.$article[0][1].'">';
                $bloc .= '<input type="submit" value="Modifier le fichier" name="submit">';
                $bloc .= '</form>';
                $bloc .= '<a class=bouton name=bouton_annuler_modification href="">Annuler</a>';
            }
            else {
                $bloc .= '<textarea COLS="100">'.utf8_encode($element [1]).'</textarea>';
            }
            
            $bloc .= '</span>';

            /* Ajout des boutons de modification
             * Ceux-ci sont des liens mais il ne pointent vers rien :
             * leur action est traitée dans script.js (js.php) en fonction de leur classe.
             */
            $bloc .='<span class=boutons_modifications>
                            <a class=bouton name=bouton_modifier href="">Modifier</a>
                            <a class=bouton name=bouton_supprimer href="">Supprimer</a>
                            <a class=bouton name=bouton_ajouterDessus href="">Aj. au-dessus</a>
                            <a class=bouton name=bouton_ajouterDessous href="">Aj. au-dessous</a>
                            <a class=bouton name=bouton_deplacerHaut href="">Dép. vers le haut</a>
                            <a class=bouton name=bouton_deplacerBas href="">Dép. vers le bas</a>
                            </span>';
            $bloc .='<span class=boutons_validation>
                            <a class=bouton name=bouton_valider_modification href="">Enregistrer</a>
                            <a class=bouton name=bouton_annuler_modification href="">Annuler</a>
                            </span>';

            $bloc .='</div>';
            }  
        }
        echo $bloc;
    }
}

?>