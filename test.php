<!DOCTYPE html>
<html>
<head>
<title>Test</title>
</head>
<body>
    <?php
$articles = array ('Titre article' => array ('<h1>','</h1>'),'Sous-titre' => array ('<h2>','</h2>'),'Paragraphe' => array ('<p>','</p>'),'Image' => array ('<img src="','">'),'Lien' => array ('<a href="','">','</a>'));
   
$json = json_encode ($articles);
    echo ($json);
    
   
    //$articlesNew = (json_decode($json));
    
    if(empty($_POST['fonction'])){
        echo '<br><br><br><br>patate';
    }
    
    if(!empty($_POST['mot'])){
        echo $_POST['mot'];
    }
    
    if (!empty($_POST['fonction'])&&!empty($_POST['json'])){
        echo 'i';
    $fonction = $_POST['fonction'];
    $json = $_POST['json'];
    $fonction($json);
    }
    
    
    Function getJSON(){
        return $json;
    }
    
    Function echoJSON($json){
        vardump ($json);
    }
    
    ?>
    
        <script type="text/javascript">
  
       /* var articlesJS = $.get( "test.php", function( data ) {
  $( ".result" ).html( data );
  alert( "Load was performed." );
});*/






        //var articlesJS = JSON.parse(jQuery.getJSON( 'test.php' ));
        //alert(articlesJS['Accueil'][3][1]);

        var articlesJS = [['Titre article','<h1>','</h1>'], ['Sous-titre','<h2>','</h2>']];

        var ok = confirm('envoyerJSON');
        if (ok){
            $.ajax({
            url: 'test.php',
	    type: 'POST',
            data:{mot:'patate'}
            //data: {fonction: 'echoJSON', json:articlesJS }
        });
        }
   
    </script>
    
</body>
</html>
