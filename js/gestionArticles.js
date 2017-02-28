// Fichier de modification de nom d'article

 jQuery(function($){
     
     
     $(".bouton_modifier_nom_article").on('click',function(){
         var nouveauNomArticle = prompt("Entrez un nouveau nom d'article", $(this).prev().text());
         $(this).prev().text(nouveauNomArticle);
     });
     
     
     
 });
