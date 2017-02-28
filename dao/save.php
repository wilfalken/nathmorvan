<?php

//$fonction = $_POST['fonction'];
/* unset() détruit la ou les variables dont le nom a été passé en argument var.
 * Ce n'est pas indispensable, je pense qu'il s'agit d'une sécurité.
 */
//unset($_POST['fonction']);
// Appel de la fonction
//$fonction($_POST['texte']);
//unset($_POST['texte']);

Function save($texte) {
	/*
	r : ouvre en lecture seule, et place le pointeur de fichier au début du fichier.
    r+ : ouvre en lecture et écriture, et place le pointeur de fichier au début du fichier.
    w : ouvre en écriture seule; place le pointeur de fichier au début du fichier et réduit la taille du fichier à 0. Si le fichier n'existe pas, on tente de le créer.
    w+ : ouvre en lecture et écriture; place le pointeur de fichier au début du fichier et réduit la taille du fichier à 0. Si le fichier n'existe pas, on tente de le créer.
    a : ouvre en écriture seule; place le pointeur de fichier à la fin du fichier file. Si le fichier n'existe pas, on tente de le créer.
    a+ : ouvre en lecture et écriture; place le pointeur de fichier à la fin du fichier. Si le fichier n'existe pas, on tente de le créer.
	 */
	$fichier = fopen ("../donnees/xml/date_sauvegarde.txt", "w+");
	// Ecriture du texte
	fputs ($fichier, $texte);//$texte['texte']
	// Fermeture
	fclose ($fichier);
}