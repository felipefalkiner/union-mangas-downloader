<?php

require "vendor/autoload.php";

function getChapters($url)
{
    $httpClient = new \Goutte\Client();
    $response = $httpClient->request("GET", $url);

    $chapters = [];
    $response->filter(".capitulos")->each(function ($node) use (&$chapters) {
        $chapters[] = $node->filter("a")->attr("href");
    });

    return array_reverse($chapters);
}

function getTitle($url)
{
    $httpClient = new \Goutte\Client();

    $response = $httpClient->request("GET", $url);

    $content = [];
    $response
        ->filter("div.breadcrumbs div.container div a")
        ->each(function ($node) use (&$content) {
            $content[] = $node->text();
        });

    return $content[2];
}

function downloadChapter($url, $title)
{
    $httpClient = new \Goutte\Client();
    $zip = new ZipArchive();

    $manga = str_replace(" ", "_", $title);
    $chapter = basename($url);
	
	if (!file_exists("mangas/$manga")) {
        mkdir("mangas/$manga", 0777, true);
    }

    if (!file_exists("mangas/$manga/$chapter")) {
        mkdir("mangas/$manga/$chapter", 0777, true);
    } else {
		echo "Pasta do Capítulo $chapter já encontrado, pulando!\n";
		echo "Para evitar isso, delete a pasta \"mangas\\$manga\\$chapter\"\n";
		echo "Começando Próximo Capítulo!\n";
		echo "-----------------------------------\n";
		return;
	}

    $response = $httpClient->request(
        "GET",
        $url
    );

    $pages = [];
    $response->filter(".img-manga")->each(function ($node) use (&$pages) {
        $pages[] = $node->filter("img")->attr("src");
    });

    $zip->open(
        "mangas/$manga/$manga-$chapter.cbr",
        ZipArchive::OVERWRITE | ZipArchive::CREATE
    );

    echo "Baixando $title - Cap: $chapter\n";

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
