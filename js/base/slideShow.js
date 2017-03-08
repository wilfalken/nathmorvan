
$(function(){
    setInterval(function(){
        // fondu blanc en 100 ms et changement image
        $(".slideshow ul").animate(
            {opacity: 0}, 100,
            function(){$(this).find("li:last").after($(this).find("li:first"));}
        );
        // fondu image en 700 ms
        $(".slideshow ul").animate(
                {opacity: 1}, 700);
     // Temps de latence avant de relancer l'animation
  }, 4000
          );
});


