 /* Ce script est appelé par un encadrement de condition php.
  * Si on oublie de mettre jQuery(function($), Javascript ne va
  * pas attendre l'aval ou non de php pour s'éxecuter.
  * Or, comme il s'agit d'une condition, il faut qu'il attende.
  */
 jQuery(function($){
    // Récupération de la fenêtre modale par son id
    var modal = document.getElementById('upLoadModale');

    // Récupération de la croix de fermeture
    var span = document.getElementsByClassName("close")[0];

    // Affichage de la fenêtre car le script a été appelé

    modal.style.display = "block";


    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "block";
        }
    }

 });
