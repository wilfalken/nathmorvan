<?php
$message = uploadImageSlideShow();
if ($message ==''){
    $message = uploadImage();
    if ($message ==''){
        $message = uploadFichier();
    }
}
?>

<div id="upLoadModale">
    <div id="contenuUpLoadModale">
        <?php echo $message; ?>
        <br>
        <span class="close"><b>&times</b></span>
    </div>
</div>


<?php if ($message !=''): ?>
    <?php echo $message; ?>
    <!-- Déclaration du script sous condition qu'il y a bien quelque chose à afficher -->
    <script src="../js/admin/uploadModale.js"></script>
<?php endif; ?>

