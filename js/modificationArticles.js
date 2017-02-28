/* On met tout le code dans cette fonction qui attend que le document soit
 * int�gralement charg� avant de lancer les instructions.
 */

 jQuery(function($){
	
	// Passage de variable PHP
	//var test = '<?php //echo ($articles['Accueil'][3][1]);?>';
	//var testarray = '<?php //echo ($testarrayjs);?>';
	//str.split("#");
	//var testnumber = parseInt('<?php //echo ($testnumber);?>');
	//alert (testnumber);



	//var article = convertPHPtoJavascript();
	
	$(".bouton").on('click',function(){
		
                //alert($(this).parent().parent().attr('data-numero'));
		switch (this.name) {
		
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
				break;
				
			case 'bouton_supprimer':
                                var confirmationSuppression = confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');
                                // Suppression de la <div class=element>
                                if (confirmationSuppression){
				$(this).parent().parent().remove();
                                }
				break;
				
			case 'bouton_ajouterDessus':
                                // Permet d'ajouter une <div class=element> avant this
                                var numero = 0;
				$(this).parent().parent().before(ajouterElement('Texte en cours de rédaction', numero));
                                $(this).parent().parent().prev().addClass('modification');
                                $(this).parent().parent().prev().find('.insertion').show();
                                $(this).parent().parent().prev().find('.boutons_modifications').hide();
                                $(this).parent().parent().prev().find('.texteAffiche').hide();
				break;
				
			case 'bouton_ajouterDessous':
                                // Permet d'ajouter une <div class=element> après this
                                var numero = 0;
				$(this).parent().parent().after(ajouterElement('Texte en cours de rédaction', numero));
                                $(this).parent().parent().next().addClass('modification');
                                $(this).parent().parent().next().find('.insertion').show();
                                $(this).parent().parent().next().find('.boutons_modifications').hide();
                                $(this).parent().parent().next().find('.texteAffiche').hide();
				break;
				
			case 'bouton_deplacerHaut':
				// On teste si l'élément précédent n'est pas le titre de l'article.
				if($(this).parent().parent().prev().html().indexOf('<a class="bouton"')!==-1){
					// On stocke les valeurs comprises entre <div class=element> et </div>.
					var elementCourant= $(this).parent().parent().html().valueOf();
					$(this).parent().parent().prev().before(copierElement(elementCourant));
					$(this).parent().parent().remove();
				};
				break;
				
			case 'bouton_deplacerBas':
				// On teste si l'élément n'est pas le dernier.
				if($(this).parent().parent().next().html()!==null){
					// On stocke les valeurs comprises entre <div class=element> et </div>.
					var elementCourant= $(this).parent().parent().html().valueOf();
                                        var numero = 0;
					$(this).parent().parent().next().after(copierElement(elementCourant, numero));
					$(this).parent().parent().remove();
				};
				break;

			case 'bouton_valider_modification':
				/* Enregistrer les modifications dans une variable (accès textarea avec val())...
                                 * (find('*') ou find('textarea') permet de trouver l'enfant - pas de méthode child())
                                 */
				var texteModifie = $(this).parent().prev().prev().find('*').val();
                                // ... et remplacement du texte (avec text())
				$(this).parent().prev().prev().prev().find('*').text(texteModifie);
				// Afficher texte
                                $(this).parent().prev().prev().prev().show();
                                // Masquer textarea
                                $(this).parent().prev().prev().hide();
                                // Afficher 6 boutons modification
                                $(this).parent().prev().show();
				// Masquer 2 boutons modification texte
				$(this).parent().hide();
                                $(this).parent().parent().removeClass('modification');
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
                                $(this).parent().parent().removeClass('modification');s
				break;
                                
                        case 'bouton_valider_insertion':
                            // Récupération du choix de l'utilisateur
                            var baliseChoisie = ($(this).prev().find(":selected").text());
                            $(this).parent().parent().next().find('.insertion').hide();
                            $(this).parent().parent().next().find('.boutons_modifications').show();
                            $(this).parent().parent().next().find('.texteAffiche').show();
                            $(this).parent().parent().removeClass('modification');
                            //alert("choix type balise : "+baliseChoisie);
                            break;
		
		
		}
		sauvegardeModif();
		/* La ligne suivante permet de bloquer le rafraichissement de la page,
		 * ce qui implique un renvoi de la page avant modification (deserialization du XML).
		 * Puisque le XML sera modifié, ce ne sera pas gênant
		 * mais cela permet d'éviter de tout recharger et donc de gagner en fluidité au moment du développement.
		 */
		return false;
	});


	


	// Fonction utilisée pour déplacer les éléments
	function copierElement(texte, numero){
		return '<div class=element data-numero='+numero+'>'+texte+'</div>';
	}

	// Intégrer toutes les boutons et la textarea depuis une fonction ou une variable
	function ajouterElement(texte, numero){
		return '<div class=element data-numero='+numero+'>'
		+'<span class=texteAffiche><p>'+texte+'</p></span>'
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
                +'<span class=insertion>'
                +'<br><select name=type>'
                +'      <option>Titre article</option>'
                +'      <option>Sous-titre</option>'
                +'      <option>Paragraphe</option>'
                +'      <option>Image</option>'
                +'      <option>Lien</option>'
                +'</select><br><br>'
                +'      <a class=bouton name=bouton_valider_insertion href="">Valider le type</a>'
                +'      <a class=bouton name=bouton_supprimer href="">Annuler</a>'
                +'</span>'
		+'</div>';
	}


	
	function sauvegardeModif(){
	//articlePHP = convert(article);
	$.ajax({
		/* L'objectif ici est de lancer une fonction PHP avec des arguments
		 * Lien vers le fichier PHP qui contient la fonction
		 * mais �galement la r�cup�ration des �l�ments via la m�thode POST
		 */
		 
	    //url: '../admin/save.php',
            url: '../dao/articles_dao_write.php',
	    type: 'POST',
            data: {fonction: 'enregistrerArticle'}, // paramètre fonction qui détermine la fonction qui sera exécutée
	    //data: {fonction: 'save', texte: texte}, // paramètre fonction qui détermine la fonction qui sera exécutée
	    //data: {fonction: 'enregistrerArticle', nomArticleModifie:nomArticle, elementsArticle: JSON.stringify(articleJS)},
	    success: function(data) {
	        //alert('Données sauvegardées !');
	    }
	});
	}



	
	
	
	
	
	
/* Fin de la fonction $(function(){});
 * qui est l'�quivalent de jQuery(document).ready(function(){});
 */
});


