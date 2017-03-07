<?php
$target_dir = "../donnees/fichiers/";
// Ne pas oublier la gestion de l'utf8
$target_file = $target_dir . utf8_decode (basename($_FILES["fileToUpload"]["name"]));
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual file or fake file

if(isset($_POST["submit"])) {
    $check = filesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "Le fichier est bien valide - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "Le fichier n'est pas valide.";
        $uploadOk = 0;
    }
}


// Check if file already exists
if (file_exists($target_file)) {
    echo "Désolé, un fichier portant le même nom est déjà présent.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Désolé, votre fichier est trop lourd.";
    $uploadOk = 0;
}
// Allow certain file formats
if($fileType != "pdf" && $fileType != "odt" && $fileType != "odg" ) {
    echo "Désolé, seules les fichiers de types PDF, ODT & ODG sont autorisées.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Désolé, le chargement n'est pas possible.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Votre fichier ". basename( $_FILES["fileToUpload"]["name"]). " a bien été chargé.";
    } else {
        echo "Désolé, il y a eu une erreur lors du chargement.";
    }
}
?>

