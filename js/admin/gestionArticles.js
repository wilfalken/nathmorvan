

jQuery(function ($) {


    $(".bouton_gestion_article").on('click', function () {
        var nomArticle = $(this).parent().parent().prev().attr('data-nomArticle');
        var nomMenu = $(this).parent().parent().prev().attr('data-nomMenu');
        var idMenu = $(this).parent().parent().prev().attr('data-idMenu');
        var idArticle = $(this).parent().parent().prev().attr('data-idArticle');
        var action = this.name;
        /* Vérification que 'data-nomsMenusArticles' existe bien
         * (il n'est utilisé que pour les modifications de noms et les ajouts)
         */
        if (typeof ($(this).attr('data-nomsMenusArticles')) !== 'undefined') {
            var nomsMenusArticles = $(this).attr('data-nomsMenusArticles').split("#");
            // Suppresion du dernier élément qui est forcément vide car la chaine de caractère se termine par "#".
            nomsMenusArticles.splice(nomsMenusArticles.length - 1);
        }
        var modification = "";

        alert(" article " + idArticle + " : " + nomArticle + " | menu " + idMenu + " : " + nomMenu + " | action : " + action);

        switch (action) {
            case 'modificationNomArticle':
                // On demande à l'utilisateur d'entrer un nouveau nom
                var nouveauNomArticle = prompt("Entrez un nouveau nom d'article", nomArticle);
                // Test pour voir s'il est déjà utilisé, si non utilisé, indexOf retourne -1
                if (nomsMenusArticles.indexOf(nouveauNomArticle) != -1 && nouveauNomArticle != nomArticle) {
                    alert("Ce nom est déjà utilisé.\nChoisissez-en un autre.");
                }
                // S'il est différent de vide ou null (si annulation), alors il est sauvegardé.
                else if (nouveauNomArticle != '' && nouveauNomArticle != null) {
                    enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, nouveauNomArticle);
                }
                break;

            case 'modificationNomMenu':
                // On demande à l'utilisateur d'entrer un nouveau nom
                var nouveauNomMenu = prompt("Entrez un nouveau nom du menu", nomMenu);
                // Test pour voir s'il est déjà utilisé, si non utilisé, indexOf retourne -1
                if (nomsMenusArticles.indexOf(nouveauNomMenu) != -1 && nouveauNomMenu != nomMenu) {
                    alert("Ce nom est déjà utilisé.\nChoisissez-en un autre.");
                }
                // S'il est différent de vide ou null (si annulation), alors il est sauvegardé.
                else if (nouveauNomMenu != '' && nouveauNomMenu != null) {
                    enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, nouveauNomMenu);
                }
                break;

            case 'suppressionArticle':
                var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet article ?");
                if (confirmation) {
                    enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, modification);
                }
                break;

            case 'suppressionMenu':
                var confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce menu ?");
                if (confirmation) {
                    enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, modification);
                }
                break;

            case 'deplacerArticleVersHaut':
                enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, modification);
                break;

            case 'deplacerArticleVersBas':
                enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, modification);
                break;

            case 'deplacerMenuVersHaut':
                enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, modification);
                break;

            case 'deplacerMenuVersBas':
                enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, modification);
                break;

            case 'ajouterArticleAuDessus':
                var nouvelArticle = prompt("Entrez un nouveau nom d'article");
                if (nomsMenusArticles.indexOf(nouvelArticle) != -1) {
                    alert("Ce nom est déjà utilisé.\nChoisissez-en un autre.");
                } else if (nouvelArticle != '' && nouvelArticle != null) {
                    enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, nouvelArticle);
                }
                break;

            case 'ajouterArticleAuDessous':
                var nouvelArticle = prompt("Entrez un nouveau nom d'article");
                if (nomsMenusArticles.indexOf(nouvelArticle) != -1) {
                    alert("Ce nom est déjà utilisé.\nChoisissez-en un autre.");
                } else if (nouvelArticle != '' && nouvelArticle != null) {
                    enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, nouvelArticle);
                }
                break;

            case 'ajouterMenuAuDessus':
                var nouveauMenu = prompt("Entrez un nouveau nom d'article");
                if (nomsMenusArticles.indexOf(nouveauMenu) != -1) {
                    alert("Ce nom est déjà utilisé.\nChoisissez-en un autre.");
                } else if (nouveauMenu != '' && nouveauMenu != null) {
                    enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, nouveauMenu);
                }
                break;

            case 'ajouterMenuAuDessous':
                var nouveauMenu = prompt("Entrez un nouveau nom d'article");
                if (nomsMenusArticles.indexOf(nouveauMenu) != -1) {
                    alert("Ce nom est déjà utilisé.\nChoisissez-en un autre.");
                } else if (nouveauMenu != '' && nouveauMenu != null) {
                    enregistrerModification(nomArticle, nomMenu, action, idArticle, idMenu, nouveauMenu);
                }
                break;


        }

        return true;


    });


    function enregistrerModification(nomArticle, nomMenu, actionBouton, idArticle, idMenu, modification) {
        $.ajax({
            /* L'objectif ici est d'envoyer à l'index
             * des éléments qui seront traités lors de leur
             * récupération par la méthode POST.
             */
            url: '../admin/index.php',
            type: 'POST',
            data: {fonction: 'gestionArticle', nomArticle: nomArticle, nomMenu: nomMenu, actionBouton: actionBouton, idArticle: idArticle, idMenu: idMenu, modification: modification}
        });
    }





});
 