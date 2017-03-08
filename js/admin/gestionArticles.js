// Fichier de modification de nom d'article

 jQuery(function($){
     
     
     $(".bouton_modifier_nom_article").on('click',function(){
         var ancienNomArticle = $(this).prev().text();
         var nouveauNomArticle = prompt("Entrez un nouveau nom d'article", ancienNomArticle);
         $(this).prev().text(nouveauNomArticle);
         sauvegardeNomArticle(ancienNomArticle,nouveauNomArticle);
     });
     
     	function sauvegardeNomArticle(ancienNomArticle,nouveauNomArticle){
	//articlePHP = convert(article);
	$.ajax({
		/* L'objectif ici est de lancer une fonction PHP avec des arguments
		 * Lien vers le fichier PHP qui contient la fonction
		 * mais �galement la r�cup�ration des �l�ments via la m�thode POST
		 */
            url: '../dao/articles_dao_write.php',
	    type: 'POST',
            data: {fonction: 'renommerArticle', ancienNomArticle: ancienNomArticle, nouveauNomArticle: nouveauNomArticle}
	});
	}
     
     
 });
