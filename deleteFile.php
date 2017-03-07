<?php
copy ('../donnees/images/carrousel/*.*' , '../donnees/images/carrousel/_old/*.*' );
unlink('../donnees/images/carrousel/*.*');

copy ('../donnees/images/*.*' , '../donnees/images/_old/*.*' );
unlink('../donnees/images/*.*');

copy ('../donnees/images/fichiers/*.*' , '../donnees/images/fichiers/_old/*.*' );
unlink('../donnees/images/fichiers/*.*');
