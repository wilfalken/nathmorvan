jQuery(function ($) {

    $(".inputFile").on('change', function () {
        document.getElementById("affichageFichierChoisi").innerHTML = "Fichier choisi : " + document.getElementById("file").value;
        $(this).next().next().next().next().next().removeClass("invisible");
    });
    


});
