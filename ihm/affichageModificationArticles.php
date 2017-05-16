<?php

Function afficher_modifier_article($article) {
    include_once ('../donnees/balisesArticles.php');

    // Affichage de l'article mis en forme selon ses balises.
    foreach ($article as $id => $element) {
        $classeModification = "";
        if ($element [0] == 'temp') {
            $bloc = '<div class="element modification" data-numero=' . $id . '>' . $balisesArticles [$element [0]] . '</div>';
        } else {
            // Gestion des liens et fichiers qui comportent trois balises
            if (($element [0] == 'Fichier') || ($element [0] == 'Lien') || ($element [0] == 'Image')) {
                // Si c'est un fichier, on affiche le nom sans l'identifiant et le type de fichier (extension)
                if ($element [0] == 'Fichier') {
                    $texteVisible = explode("_[", $element[1])[0] . " - Fichier de type " . explode("].", $element[1])[1];
                } else {
                    $texteVisible = $element[1];
                }
                // Récupération des balises d'ouverture, centrale et de fermeture
                $in = $balisesArticles [$element [0]] [0];
                $mid = $balisesArticles [$element [0]] [1];
                $out = $balisesArticles [$element [0]] [2];
                // Affichage de chaque élément
                $bloc = $in . $element[1] . $mid . $texteVisible . $out;
            }
            // Gestion du carrousel qui est un ensemble de balise
            else if ($element [0] == 'Carrousel') {
                $bloc = $balisesArticles [$element [0]];
            }
            // ... pour tous les autres, deux balises suffisent
            else {
                // Récupération des balises d'ouverture et de fermeture
                $in = $balisesArticles [$element [0]] [0];
                $out = $balisesArticles [$element [0]] [1];
                // Affichage de chaque élément
                $bloc = $in . ($element [1]) . $out;
            }
            /* Si ce n'est pas un Titre article (h1),
             * on met le bloc dans un span et on ajoute
             * les boutons de modification.
             * On ne traite pas le titre de l'article,
             * il est modifiable par la gestion du nombre d'article.
             */
            if ($element [0] != 'Titre article') {
                // Gestion d'un espace supplémentaire pour les liens
                $ajoutClass = "";
                if ($element [0] == 'Lien') {
                    $ajoutClass = ' lien';
                }
                if ($element [0] == 'Fichier') {
                    $ajoutClass = ' fichier';
                }
                $bloc = '<div class="element" data-numero=' . $id . ' data-balise=' . $element [0] . '><span class="texteAffiche' . $ajoutClass . '">' . $bloc . '</span>';

                /* Ici commence la zone permettant les modifications.
                 * Par défaut, il s'agit de texte, on va donc masquer le texte affiché
                 * et affiché une textarea permettant la modification.
                 * S'il s'agit de fichier à remplacer (type image ou texte),
                 * la zone de modification sera un formulaire permettant l'upload.
                 */
                $bloc .= '<span class="texteModifiable' . $ajoutClass . '">';
                if ($element [0] == 'Image') {
                    $bloc .= '<p style="font-size: small;text-align:center;color:black;">Image actuelle : ' . $article[$id][1] . '</p>';
                    $bloc .= '<form  class="upload" action="" method="post" enctype="multipart/form-data">';
                    $bloc .= '<span class="textInput">Choisisez l\'image à charger : </span>';
                    $bloc .= '<input type="file" id="file' . $id . '" name="uploadImage"  class="inputFile">';
                    $bloc .= '<label for="file' . $id . '" class="labelInput">Choisir une image ...</label>';
                    $bloc .= '<label for=" " id="affichageFichierChoisi' . $id . '" class="textInput">Aucune image sélectionnée</label>';
                    // On cache dans le formulaire l'id de l'élément modifié
                    $bloc .= '<input type="hidden" name="idImage" value="' . $id . '">';
                    // Ainsi que le nom de l'article
                    $bloc .= '<input type="hidden" name="nomArticle" value="' . $article[0][1] . '">';
                    $bloc .= '<input type="submit" id="modifierFichierChoisi' . $id . '" value="Modifier l\'image" name="submit" disabled>';
                    $bloc .= '</form>';
                    $bloc .= '<a class="bouton center" name=bouton_annuler_modification href="">Annuler</a><br>';
                } else if ($element [0] == 'Fichier') {
                    $bloc .= '<br>';
                    $bloc .= '<p style="font-size: small;text-align:center;color:black;">Fichier actuel : ' . $article[$id][1] . '</p>';
                    $bloc .= '<form  class="upload" action="" method="post" enctype="multipart/form-data">';
                    $bloc .= '<span class="textInput">Choisisez le fichier à charger : </span>';
                    $bloc .= '<input type="file" id="file' . $id . '" name="uploadFichier" class="inputFile">';
                    $bloc .= '<label for=" " id="affichageFichierChoisi' . $id . '" class="textInput">Aucun fichier sélectionné</label>';
                    $bloc .= '<label for="file' . $id . '" class="labelInput">Choisir un fichier ...</label>';
                    // On cache dans le formulaire l'id de l'élément modifié
                    $bloc .= '<input type="hidden" name="idFichier" value="' . $id . '">';
                    // Ainsi que le nom de l'article
                    $bloc .= '<input type="hidden" name="nomArticle" value="' . $article[0][1] . '">';
                    $bloc .= '<input type="submit" id="modifierFichierChoisi' . $id . '" value="Modifier le fichier" name="submit" disabled>';
                    $bloc .= '</form>';
                    $bloc .= '<a class="bouton center" name=bouton_annuler_modification href="">Annuler</a><br>';
                } else {
                    $style = "";
                    $nbcols = "100";
                    $nblignes = "10";
                    if ($element [0] == 'Sous-titre') {
                        $style = 'style="font-weight: 600; font-style: normal; font-size: 1.6em; color: #63c000;"';
                        $nbcols = "50";
                        $nblignes = "1";
                    }
                    $bloc .= '<textarea rows="' . $nblignes . '" cols="' . $nbcols . '" ' . $style . '>' . ($element [1]) . '</textarea><br>';
                }

                $bloc .= '</span>';

                /* Ajout des boutons de modification
                 * Ceux-ci sont des liens mais il ne pointent vers rien :
                 * leur action est traitée dans script.js (js.php) en fonction de leur classe.
                 */
                $bloc .= '<span class=boutons_modifications>
                            <a class=bouton name=bouton_modifier href="">Modifier</a>
                            <a class=bouton name=bouton_supprimer href="">Supprimer</a>
                            <a class=bouton name=bouton_ajouterDessus href="">Aj. au-dessus</a>
                            <a class=bouton name=bouton_ajouterDessous href="">Aj. au-dessous</a>
                            <a class=bouton name=bouton_deplacerHaut href="">Dép. vers le haut</a>
                            <a class=bouton name=bouton_deplacerBas href="">Dép. vers le bas</a>
                            </span>';
                $bloc .= '<span class=boutons_validation>
                            <a class=bouton name=bouton_valider_modification href="">Enregistrer</a>
                            <a class=bouton name=bouton_annuler_modification href="">Annuler</a>
                            </span>';

                $bloc .= '</div>';
            }
        }
        echo $bloc;
    }
}

?>