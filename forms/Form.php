<?php
namespace forms;

use Database;
use InsertForm;
use PageInterface;

include_once __DIR__ . '/../db/Database.php';

require_once __DIR__ . '/../config/Config.php';
require_once __DIR__ . '/../PageInterface.php';
class Form implements PageInterface {
    private InsertForm $insertForm;
    private Database $db;

    private array $fields;

    public function __construct(Database $db) {
        $this->db = $db;
        $this->insertForm = new InsertForm(); // Создание нового объекта InsertForm и присваивание его свойству объекта
        $this->fields = $this->insertForm->handleRequest();
        if ($this->isAllValid($this->fields)) {
            $this->insertForm->insertIntoTable($this->fields, $this->db->conn);
        }

    }

    public function isAllValid(array $dataTemplate): bool {
        foreach ($dataTemplate as &$field) {
            if ($field['required'] && empty($field['value'])) {
                $field['isValid'] = false;
            } else {
                $field['isValid'] = true;
            }

            if (!$field['isValid']) {
                return false;
            }
        }
        return true;
    }
    public function getHtml(): string {
        $html = '<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8">
    <title>Добавление пользователя</title>
    <style type="text/css">
        .error { border: 2px solid #ff0000; }
        .req:valid { border: 2px solid #000000; }
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); width: 400px; }
        label { font-weight: bold; margin-bottom: 5px; }
        input { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        input.req { outline: none; }
        input[type="submit"] { background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    </style>
    </head>
    <body>
    <form action="/form" method="post" id="userForm">';
        foreach ($this->fields as $field) {
            $class = $field['isValid'] ? "req" : "error";
            $html .= '<label for="' . $field['id'] . '">' . $field['label'] . ':</label>';
            $html .= '<input type="' . $field['type'] . '" id="' . $field['id'] . '" name="' . $field['name'] . '" value="' . $field['value'] .'" class="'.$class.'"><br>';
        }
        $html .= '<input type="submit" name="submit" value="Добавить пользователя" id="button">';
        $html .= '</form></body></html>';
        return $html;
    }
}