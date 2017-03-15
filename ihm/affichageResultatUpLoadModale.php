<?php
// Si le message n'est pas vide (= il y a bien eu une tentative d'upload), on l'affiche
if (!empty ($message)):
?>

    <script>alert('<?php echo $message; ?>');</script>
    
<?php
endif;
?>

