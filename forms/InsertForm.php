<?php

include_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../config/Config.php';
include_once __DIR__ . '/../forms/form.php';
use config\Config;

$config = new Config('config.ini');
$db = new Database($config);

/**
 * @method getDataFromFormAndUpdateTableTemplate()
 */
class InsertForm {

    public function getTemplate(): array {
        return [
            ['id' => 'last_name', 'name' => 'last_name', 'label' => 'Фамилия', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
            ['id' => 'first_name', 'name' => 'first_name', 'label' => 'Имя', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
            ['id' => 'middle_name', 'name' => 'middle_name', 'label' => 'Отчество', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
            ['id' => 'age', 'name' => 'age', 'label' => 'Возраст', 'type' => 'number', 'value' => '', 'required' => true, 'isValid' => true]
        ];
    }
    public function getDataFromFormAndUpdateTemplate(): array {
        $fields = $this->getTemplate();
        $result = [];
        foreach ($fields as $field) {
            if (empty($_POST[$field['name']])) {
                $field['value'] = '';
                $field['isValid'] = false;
            } else {
                $field['value'] = $_POST[$field['name']];
                $field['isValid'] = true;
            }
            $result[] = $field;
        }
        return $result;
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
    public function insertIntoTable(array $dataTemplate, $con): void {
        $columns = implode(", ", array_map(function($item) {
            return "`" . $item['name'] . "`";
        }, $dataTemplate));

        $values = implode(", ", array_map(function($item) use ($con) {
            return "'" . $con->real_escape_string($item['value']) . "'";
        }, $dataTemplate));

        $sql = "INSERT INTO `name` ($columns) VALUES ($values)";

        if ($con->query($sql)) {
            header("Location: ../data/Table.php");
            if (ob_get_length()) ob_end_flush();
            exit;
        } else {
            echo "Ошибка: " . $sql . "<br>" . $con->error;
        }
    }

    /**
     * @throws Exception
     */
    public function handleRequest(): array
    {
        ob_start();
        if (isset($_POST['submit'])) {
            $fields = $this->getDataFromFormAndUpdateTemplate();
            if ($this->isAllValid($fields)) {
                $config = new Config(__DIR__ . '/../config.ini');
                $db = new Database($config);
                $this->insertIntoTable($fields, $db->conn);
            }
        } else {
            $fields = $this->getTemplate();
        }
        return $fields;
    }
}
