<?php

for ($i = 1; $i <= 197; $i++) {
    require "vendor/autoload.php";
    $httpClient = new \Goutte\Client();
    $zip = new ZipArchive();

    $manga = "Solo_Leveling";
    $chapter = str_pad($i, 2, "0", STR_PAD_LEFT);

    $response = $httpClient->request(
        "GET",
        "https://unionleitor.top/leitor/$manga/$chapter"
    );

    $pages = [];
    $response->filter(".img-manga")->each(function ($node) use (&$pages) {
        $pages[] = $node->filter("img")->attr("src");
    });

    if (!file_exists("mangas/$manga")) {
        mkdir("mangas/$manga", 0777, true);
    }

    if (!file_exists("mangas/$manga/$chapter")) {
        mkdir("mangas/$manga/$chapter", 0777, true);
    }

    $zip->open(
        "mangas/$manga/$manga-$chapter.cbr",
        ZipArchive::OVERWRITE | ZipArchive::CREATE
    );

    echo "Baixando $manga - Cap: $chapter\n";

    foreach ($pages as $page) {
        $filename = basename($page);
        $download = "mangas/$manga/$chapter/$filename";

        if (
            strpos($page, "banner_scan.png") == true ||
            strpos($page, "banner_forum.png") == true
        ) {
            continue;
        }

        $ch = curl_init($page);
        $fp = fopen($download, "wb");
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $zip->addFile("mangas/$manga/$chapter/$filename", $filename);
    }

    $zip->close();

    echo "$manga Cap: $chapter baixado e CBR criado!\n";
    echo "Começando Próximo Capítulo!\n";
    echo "-----------------------------------\n";
}

echo "DOWNLOADS FINALIZADOS";
