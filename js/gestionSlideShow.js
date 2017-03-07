/* On met tout le code dans cette fonction qui attend que le document soit
 * intégralement chargé avant de lancer les instructions.
 */

jQuery(function($){
    $(".bouton_slideshow").on('click',function(){
       var nomFichier = $(this).attr('data-nomFichier');
       var confirmation = confirm ('Voulez-vous supprimer '+nomFichier+' ?');
       if (confirmation){
       supprimerFichier(nomFichier); 
       }
        
    });
    
    	function supprimerFichier(nomFichier){
	$.ajax({
		/* L'objectif ici est de lancer une fonction PHP avec des arguments
		 * Lien vers le fichier PHP qui contient la fonction
		 * mais également la récupération des éléments via la méthode POST
		 */
		 
	    //url: '../admin/save.php',
            url: '../dao/deleteImageSlideShow.php',
	    type: 'POST',
            data: {supprimerImage: 'supprimerImage', fonction: 'supprimerImageCarrousel', nomImageASupprimer: nomFichier},
	    success: function(data) {
	        //alert('Données sauvegardées !');
	    }
	});
	}
    
    
    
    
    
    
    
    
});


