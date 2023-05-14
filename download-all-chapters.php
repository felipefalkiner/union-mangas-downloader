<?php

require_once 'functions.php';

//Mudar a URL do Mangá aqui:
$url = "https://unionleitor.top/pagina-manga/boku-no-hero-academia-pt-br";

$chapters = getChapters($url);

$title = getTitle($chapters[0]);

foreach ($chapters as $chapter) {
    downloadChapter($chapter, $title);
}

echo "DOWNLOADS TERMINADOS";
