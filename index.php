<?php
use config\Config;
include_once 'db/Database.php';
include_once 'HomePage.php';
include_once 'config/Page.php';
include_once 'data/Table.php';
include_once 'forms/Form.php';


$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$searchInPage = array_search($request, \config\Page::LINKS);
if ($searchInPage) {
    $result = router($request);


    if (isset($_GET['deleteId'])) {
        $config = new Config('config.ini');
        $db = new Database($config);
        $db->deleteRecord((int)$_GET['deleteId']);
    }

    echo $result->getHtml();
}

function router(string $uri): PageInterface
{
    return match ($uri) {
        '/table' => (new Table(new Database(new Config('config.ini')))),
        '/tableTwo' => (new Table(new Database(new Config('config.ini')))),
        '/' => (new HomePage()),
        '/form' => (new \forms\Form(new Database(new Config('config.ini')))),
        default => new NotFoundHttp()
    };
}