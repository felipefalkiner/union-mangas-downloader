# Union Mangas Downloader

This is a simple script that get manga chapter images from a manga reading site, download it to a folder that has the chapter's name and create a CBR file for usage on CBR Readers.

## Usage

 - Install the dependencies with composer.
 - Edit manga.php
 - - The "for loop" on line 3 is the chapter range, so if you want to download from chapters 15 to 30, those are your values.
 - - $manga on line 8 is the Manga name based on a chapter URL
 - Run manga.php