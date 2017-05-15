/* On met tout le code dans cette fonction qui attend que le document soit
 * intégralement chargé avant de lancer les instructions.
 */

jQuery(function($){
	$(".bouton").on('click',function(){
                /* Définition des six variables nécessaires à la sauvegarde des modifications.
                 * Celle enregistrant le contenu de l'élément modifié sera mise à jour
                 * ou non en fonction de l'action
                 * (par exemple, lors de la suppression, cela ne sert à rien).
                 */
		var nomArticleModifie = $(this).parent().parent().parent().find('h1').text();
                var idElementModifie = $(this).parent().parent().attr('data-numero');
                var actionElement = this.name;
                var elementModifie = '';
                var baliseModifiee = '';
                var baliseFromPHP = $(this).parent().parent().attr('data-balise');

                
                /* Selon le nom du bouton sur lequel l'utilisateur a cliqué,
                 * on a une ou plusieurs actions
                 * avec ou non la sauvegarde de la modification.
                 */
                
                /* "return false" permet de bloquer le rafraichissement de la page,
		 * ce qui implique un renvoi de la page avant modification (deserialization du XML).
		 * Puisque le XML sera modifié, ce ne sera pas gênant
		 * mais cela permet d'éviter de tout recharger et donc de gagner en fluidité au moment du développement.
                 * "return true" force le rafraissiment.
		 */
                
		switch (actionElement) {
		
			case 'bouton_modifier':
                            if (baliseFromPHP === 'Carrousel'){
                                alert ("Pour modifier le carrousel d'images,\nmerci de consulter la page \"Gestion carrousel\".");
                            }
                            else {
                                $(this).parent().parent().addClass('modification');
                                
                                if (baliseFromPHP != 'Image' && baliseFromPHP != 'Fichier'){
                                    // Masquer texte
                                    $(this).parent().prev().prev().hide();
                                    // Afficher 2 boutons modification texte
                                    $(this).parent().next().show();
                                }
                                // Afficher textarea
				$(this).parent().prev().show();
				// Masquer 6 boutons modification
				$(this).parent().hide();

                                /* On n'enregistre pas les modifications ici, il s'agit juste d'une bascule de l'affichage
                                 * qui n'a pas à être rafraichit (=perte modification affichage DOM)
                                 */
                                return false;
                            }
                            break;
				
			case 'bouton_supprimer':
                                
                                // Penser à mettre des " et non des ', sinon ce n'est pas pris en compte
                                var confirmationSuppression = confirm("Êtes-vous sûr de vouloir supprimer cet élément ?");
                                if (confirmationSuppression){
                                    // Enregistrement de la suppression
                                    enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie, baliseModifiee);
                                }
                                // Rafraichissement du DOM
                                return true;
				break;
				
			case 'bouton_ajouterDessus':
                                // Enregistrement de l'id qui servira de point de repère pour l'insertion
                                enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie, baliseModifiee);
                                // Rafraichissement du DOM
                                return true;
				break;
				
			case 'bouton_ajouterDessous':
                                // Enregistrement de l'id qui servira de point de repère pour l'insertion
                                enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie, baliseModifiee);
                                // Rafraichissement du DOM
                                return true;
				break;
				
			case 'bouton_deplacerHaut':
				// On teste si l'élément précédent n'est pas le titre de l'article.
				if($(this).parent().parent().prev().html().indexOf('<a class="bouton"')!==-1){
                                        // Enregistrement du déplacement
                                        enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie, baliseModifiee);
                                        // Rafraichissement du DOM
                                        return true;
				};
				break;
				
			case 'bouton_deplacerBas':
				// On teste si l'élément n'est pas le dernier.
				if($(this).parent().parent().next().html()!==null){
                                        // Enregistrement du déplacement
                                        enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie, baliseModifiee);
                                        // Rafraichissement du DOM
                                        return true;
				};
				break;

			case 'bouton_valider_modification':
				/* Enregistrer les modifications dans une variable (accès textarea avec val())...
                                 * (find('*') ou find('textarea') permet de trouver l'enfant - pas de méthode child())
                                 */
				var texteModifie = $(this).parent().prev().prev().find('*').val();
                                // ... et remplacement du texte (avec text()) si ce n'est pas vide
                                if (texteModifie !== ""){
                                    $(this).parent().prev().prev().prev().find('*').text(texteModifie);
                                }
				// Afficher texte
                                $(this).parent().prev().prev().prev().show();
                                // Masquer textarea
                                $(this).parent().prev().prev().hide();
                                // Afficher 6 boutons modification
                                $(this).parent().prev().show();
				// Masquer 2 boutons modification texte
				$(this).parent().hide();
                                $(this).parent().parent().removeClass('modification');
                                elementModifie = $(this).parent().prev().prev().prev().find('*').text();
                                enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie, baliseModifiee);
				return true;
                                break;

			case 'bouton_annuler_modification':
                                /* Note : le fait de forcer le rafraissiment suffit
                                 * pour remettre toutes les variables à zéro
                                 */
                                return true;
				break;
                                
                        case 'bouton_valider_insertion':
                            /* Récupération du choix de l'utilisateur.
                             * On ne va pas modifier le DOM.
                             * On modifie l'objet PHP et on rafraichit la page en partant de l'objet modifié.
                             */
                            baliseModifiee = $(this).parent().find(":selected").text();
                            elementModifie = getDefaultText(baliseModifiee);
                            // On enregistre le type d'élément avec une valeur par défaut
                            enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie, baliseModifiee);
                            return true;
                            break;
                            

		
		}


	});


	
	function getDefaultText(baliseChoisie){
            var texte = '';
            //var contenu;
            switch (baliseChoisie){
                case 'Sous-titre':
                    texte = 'Sous-titre en cours de modification';
                    //contenu = '<h2>'+texte+'</h2>';
                    break;
                case 'Paragraphe':
                    texte = 'Paragraphe en cours de modification';
                    //contenu = '<p>'+texte+'</p>';
                    break;
                case 'Image':
                    texte = '_image_defaut.jpg';
                    //contenu = '<img src="'+texte+'">';
                    break;
                case 'Lien':
                    texte = 'Lien en cours de modification';
                    //contenu = '<a href="index.php?article_a_afficher=Accueil">'+texte+'</a>';
                    break;
                case 'Fichier':
                    texte = '_fichier_defaut.pdf';
                    //contenu = '<a href="'+texte+'">_fichier_defaut.pdf</a>';
                    break;
                case 'Carrousel':
                    texte = '';
                    break;
            }
 
 
		//return '<div class=element><span class=texteAffiche>'+contenu+'</span></div>'
                return texte;
        // Pas besoin des boutons si on rafraichit juste après avoir stocké un élément 'en cours'
                ;
	}


	
	function enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie, baliseModifiee){
	$.ajax({
		/* L'objectif ici est de lancer une fonction PHP avec des arguments
		 * Lien vers le fichier PHP qui contient la fonction
		 * mais également la récupération des éléments via la méthode POST
		 */
            url: '../admin/index.php',
	    type: 'POST',
            async: false,
            data: {fonction: 'modificationArticles', nomArticleModifie: nomArticleModifie, idElementModifie: idElementModifie, actionElement: actionElement, elementModifie: elementModifie, baliseModifiee: baliseModifiee},
	    success: function() {
	        //alert('Données sauvegardées !');
	    }
	});
	}



	
	
	
	
	
	
/* Fin de la fonction jQuery(function($){});
 * qui est l'équivalent de jQuery(document).ready(function(){}); ou de $(function(){});
 */
});


