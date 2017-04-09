<?php

switch ($_POST['fonction']) {
    case 'gestionArticles':
        include '../dao/articles_dao_write.php';
        include '../controleur/gestionArticles.php';
        break;
    case 'modificationArticles':
        include '../dao/articles_dao_write.php';
        include '../controleur/modificationArticles.php';
        break;
    case 'supprimerDuRepertoire':
        include '../dao/supprimerDuRepertoire.php';
        break;

}

