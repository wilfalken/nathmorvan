
<?php

/* Cette première partie de code n'est pas réellement de l'ihm.
 * Elle vérifie les upload et modifie les listes des éléments des articles
 * et retourne un message (ou non si la fonction n'a pas été appelée.
 * L'appel se fait en fonction des éléments passés dans le $_FILE et $_POST.
 */
$message = uploadImageSlideShow();
if ($message ==''){
    $message = uploadImage();
    if ($message ==''){
        $message = uploadFichier();
    }
}
?>
<!--
<div id="upLoadModale">
    <div id="contenuUpLoadModale">
        <?php //echo $message; ?>
        <span class="close"><b>&times</b></span>
    </div>
</div>
-->
<!-- Si le message n'est pas vide (= il y a bien eu une tentive d'upload), on l'affiche -->
<?php if ($message !=''): ?>
    <script>alert('<?php echo $message; ?>');</script>
<?php endif; ?>

