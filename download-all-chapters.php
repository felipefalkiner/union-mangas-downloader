<?php

require_once 'functions.php';

//Mudar a URL do Mangá aqui:
$url = "https://guimah.com/perfil/V0lTOGpKb0FDYVRBdERLWEU2bjVrZVFEdjcrMDhwK2VlMFllcWVKSmQ3SVg=";

$chapters = getChapters($url);

$title = getTitle($chapters[0]);

foreach ($chapters as $chapter) {
    downloadChapter($chapter, $title);
}

echo "DOWNLOADS TERMINADOS";
