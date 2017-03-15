/* On met tout le code dans cette fonction qui attend que le document soit
 * intégralement chargé avant de lancer les instructions.
 */

jQuery(function($){
    $(".bouton_repertoire").on('click', function(){
        var nomFichier = $(this).attr('data-nomFichier');
        var nomRepertoire = $(this).attr('data-nomRepertoire');
        var confirmation = confirm ('Voulez-vous supprimer ' + nomFichier + ' ?');
        if (confirmation){
            $.ajax({
            /* L'objectif ici est de lancer une fonction PHP avec des arguments
             * Lien vers le fichier PHP qui contient la fonction
             * mais également la récupération des éléments via la méthode POST
             */
            url: '../admin/index.php',
                    type: 'POST',
                    data: {fonction: 'supprimerDuRepertoire', nomFichier: nomFichier, nomRepertoire: nomRepertoire},
                    	    success: function() {
	        //alert('Données sauvegardées !');
	    }
            });
        }
    });
});
