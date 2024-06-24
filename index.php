<?php
//include_once '../db/Database.php';
use config\Config;

include_once 'HomePage.php';
include_once 'config/Page.php';

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$searchInPage = array_search($request, \config\Page::LINKS);
if ($searchInPage) {
    $result = match ($request) {
        '/table' => new Table(new Database(new Config('config.ini'))),
        '/' => (new HomePage())->getHtml(),
        '/form' => include 'forms/form.php',
        default => "404 Not Found"
    };

    echo $result;
}
?>