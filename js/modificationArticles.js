/* On met tout le code dans cette fonction qui attend que le document soit
 * intégralement chargé avant de lancer les instructions.
 */

 jQuery(function($){
	

	
	$(".bouton").on('click',function(){
                /* Définition des quatre variables nécessaires à la sauvegarde des modifications.
                 * Celle enregistrant le contenu de l'élément modifié sera mise à jour
                 * ou non en fonction de l'action
                 * (par exemple, lors de la suppression, cela ne sert à rien).
                 */
		var nomArticleModifie = $(this).parent().parent().parent().find('h1').text();
                var idElementModifie = $(this).parent().parent().attr('data-numero');
                var actionElement = this.name;
                var elementModifie = '';

                
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
                                $(this).parent().parent().addClass('modification');
                                // Masquer texte
                                $(this).parent().prev().prev().hide();
                                // Afficher textarea
				$(this).parent().prev().show();
				// Masquer 6 boutons modification
				$(this).parent().hide();
                                // Afficher 2 boutons modification texte
                                $(this).parent().next().show();
                                /* On n'enregistre pas les modifications ici, il s'agit juste d'une bascule de l'affichage
                                 * qui n'a pas à être rafraichit (=perte modification affichage DOM)
                                 */
                                return false;
				break;
				
			case 'bouton_supprimer':
                                var confirmationSuppression = confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');
                                // Suppression de la <div class=element>
                                if (confirmationSuppression){
				$(this).parent().parent().remove();
                                }
                                // Enregistrement de la suppression
                                enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie);
                                // Rafraichissement du DOM
                                return true;
				break;
				
			case 'bouton_ajouterDessus':
                                // Permet d'ajouter une <div class=element> avant this
                                //$(this).parent().parent().before(ajouterElementTemporaire());
                                // Enregistrement de l'id qui servira de point de repère pour l'insertion
                                enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie);
                                // Pas de rafraichissement du DOM
                                return true;
				break;
				
			case 'bouton_ajouterDessous':
                                // Permet d'ajouter une <div class=element> après this
                                //$(this).parent().parent().after(ajouterElementTemporaire());
                                // Enregistrement de l'id qui servira de point de repère pour l'insertion
                                enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie);
                                // Pas de rafraichissement du DOM
                                return true;
				break;
				
			case 'bouton_deplacerHaut':
				// On teste si l'élément précédent n'est pas le titre de l'article.
				if($(this).parent().parent().prev().html().indexOf('<a class="bouton"')!==-1){
					// On stocke les valeurs comprises entre <div class=element> et </div>.
					var elementCourant= $(this).parent().parent().html().valueOf();
					$(this).parent().parent().prev().before(copierElement(elementCourant));
					$(this).parent().parent().remove();
                                        // Enregistrement du déplacement
                                        enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie);
                                        // rafraichissement du DOM
                                        return true;
				};

				break;
				
			case 'bouton_deplacerBas':
				// On teste si l'élément n'est pas le dernier.
				if($(this).parent().parent().next().html()!==null){
					// On stocke les valeurs comprises entre <div class=element> et </div>.
					var elementCourant= $(this).parent().parent().html().valueOf();
					$(this).parent().parent().next().after(copierElement(elementCourant));
					$(this).parent().parent().remove();
                                        // Enregistrement du déplacement
                                        enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie);
                                        // rafraichissement du DOM
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
                                enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie);
				return true;
                                break;

			case 'bouton_annuler_modification':
                                
                                // Enregistrer le texte initial dans une variable afin de remettre le textarea à zéro
                                var texteInitial = $(this).parent().prev().prev().prev().find('*').text();
                                $(this).parent().prev().prev().find('*').val(texteInitial);
				// Afficher texte
                                $(this).parent().prev().prev().prev().show();
                                // Masquer textarea
				$(this).parent().prev().prev().hide(); 
                                // Afficher 6 boutons modification
                                $(this).parent().prev().show();
                                // Masquer 2 boutons modification texte
				$(this).parent().hide();
                                $(this).parent().parent().removeClass('modification');
                                return true;
                                
				break;
                                
                        case 'bouton_valider_insertion':
                            // Récupération du choix de l'utilisateur
                            var baliseChoisie = $(this).parent().find(":selected").text();
                            //var baliseChoisie = $(this).prev().text();
                            $(this).parent().parent().after(creerElement(baliseChoisie));
                            elementModifie = $(this).parent().parent().next().find('*').text();
                            $(this).parent().remove();
                            
                            // On enregistre le type d'élément avec une valeur par défaut
                            enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie);
                            return true;
                            break;
                            
                         /*   
                        case 'bouton_supprimer_insertion':
                            var confirmationSuppression = confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');
                            if (confirmationSuppression){
                                // Suppression de l'élément ajouté dans le DOM
                                $(this).parent().parent().remove();
                                enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie);
                                return true;
                            }
                            break;
		*/
		
		}
		

	});


	


	// Fonction utilisée pour déplacer les éléments
	function copierElement(texte){
            /* On ne met pas d'identifiant de numéro
             * puisque, comme on force le rafraichissement,
             * affichageModificationArticles.php en fournira un
             * et mettra à jour tous les autres.
             */
		return '<div class=element>'+texte+'</div>';
	}




	function creerElement(baliseChoisie){
            var texte;
            var contenu;
            switch (baliseChoisie){
                case 'Sous-titre':
                    texte = 'Titre en cours de modification';
                    contenu = '<h2>'+texte+'</h2>';
                    break;
                case 'Paragraphe':
                    texte = 'Paragraphe en cours de modification';
                    contenu = '<p>'+texte+'</p>';
                    break;
                case 'Image':
                    texte = '../donnees/images/_image_defaut.jpg';
                    contenu = '<img src="'+texte+'">';
                    break;
                case 'Lien':
                    texte = 'Lien en cours de modification';
                    contenu = '<a href="index.php?article_a_afficher=Accueil">'+texte+'</a>';
                    break;
                case 'Fichier':
                    texte = '../donnees/fichiers/_fichier_defaut.pdf';
                    contenu = '<a href="'+texte+'">_fichier_defaut.pdf</a>';
                    break;
            }
 
 
		return '<div class=element><span class=texteAffiche>'+contenu+'</span></div>'
        // Pas besoin des boutons si on rafraichit juste après avoir stocké un élément 'en cours'
        /*
                +'<span class=texteModifiable><textarea COLS="100">'+texte+'</textarea></span>'
		+'<span class=boutons_modifications>'
		+'	<a class=bouton name=bouton_modifier href="">Modifier</a>'
		+'	<a class=bouton name=bouton_supprimer href="">Supprimer</a>'
		+'	<a class=bouton name=bouton_ajouterDessus href="">Aj. au-dessus</a>'
		+'	<a class=bouton name=bouton_ajouterDessous href="">Aj. au-dessous</a>'
		+'	<a class=bouton name=bouton_deplacerHaut href="">Dép. vers le haut</a>'
		+'	<a class=bouton name=bouton_deplacerBas href="">Dép. vers le bas</a>'
		+'	</span>'
		+'<span class=boutons_validation>'
		+'	<a class=bouton name=bouton_valider_modification href="">Enregistrer</a>'
		+'	<a class=bouton name=bouton_annuler_modification href="">Annuler</a>'
		+'	</span>'
                                */
                ;
	}


	
	function enregistrerElementModifie(nomArticleModifie, idElementModifie, actionElement, elementModifie){
	//articlePHP = convert(article);
	$.ajax({
		/* L'objectif ici est de lancer une fonction PHP avec des arguments
		 * Lien vers le fichier PHP qui contient la fonction
		 * mais également la récupération des éléments via la méthode POST
		 */
		 
	    //url: '../admin/save.php',
            url: '../dao/articles_dao_write.php',
	    type: 'POST',
            data: {fonction: 'enregistrerElementModifie', nomArticleModifie: nomArticleModifie, idElementModifie: idElementModifie, actionElement: actionElement, elementModifie: elementModifie},
	    success: function(data) {
	        //alert('Données sauvegardées !');
	    }
	});
	}



	
	
	
	
	
	
/* Fin de la fonction jQuery(function($){});
 * qui est l'équivalent de jQuery(document).ready(function(){}); ou de $(function(){});
 */
});


