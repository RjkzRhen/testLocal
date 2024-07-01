<?php
use config\Config;
include_once 'db/Database.php';
include_once 'HomePage.php';
include_once 'config/Page.php';
include_once 'data/Table.php';
include_once 'forms/form.php';


$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$searchInPage = array_search($request, \config\Page::LINKS);
if ($searchInPage) {
    $result = match ($request) {
        '/table' => (new Table(new Database(new Config('config.ini'))))->getHtml(),
        '/' => (new HomePage())->getHtml(),
        '/form' => (new \forms\Form(new Database(new Config('config.ini'))))->handleRequest(),
        default => "404 Not Found"
    };
    if (isset($_GET['deleteId'])) {
        $config = new Config('config.ini');
        $db = new Database($config);
        $db->deleteRecord((int)$_GET['deleteId']);
    }

        echo $result;
}
