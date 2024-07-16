<?php

include_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../config/Config.php';
include_once __DIR__ . '/../forms/Form.php';
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

    public function insertIntoTable(array $dataTemplate, $con): void {
        $columns = implode(", ", array_map(function($item) {
            return "`" . $item['name'] . "`";
        }, $dataTemplate));

        $values = implode(", ", array_map(function($item) use ($con) {
            return "'" . $con->real_escape_string($item['value']) . "'";
        }, $dataTemplate));

        $sql = "INSERT INTO `name` ($columns) VALUES ($values)";

        if ($con->query($sql)) {
            header("Location: /table");
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
        if (isset($_POST['submit'])) { // Проверяет, была ли отправлена форма
            $fields = $this->getDataFromFormAndUpdateTemplate(); // Извлекает и обновляет данные формы

        } else {
            $fields = $this->getTemplate(); // Получает шаблон формы с пустыми значениями, если форма не отправлена
        }
        return $fields; // Возвращает поля формы
    }
}
/*
 * handleRequest
 */