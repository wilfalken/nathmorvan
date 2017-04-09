jQuery(function ($) {

// Si l'utilisateur a sélectionné un fichier, on réactive le bouton d'upload
    $(".inputFile").on('change', function () {
        var idElementModifie = $(this).parent().parent().parent().attr('data-numero');
        if (idElementModifie != undefined) {
            document.getElementById("affichageFichierChoisi" + idElementModifie).innerHTML = "Fichier choisi : " + this.value;
            document.getElementById("modifierFichierChoisi" + idElementModifie).disabled =false;
        }
        else
        {
            document.getElementById("affichageFichierChoisi").innerHTML = "Fichier choisi : " + this.value;
            document.getElementById("modifierFichierChoisi").disabled =false;
        }
    });



});
