<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Таблица пользователей</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .age-over-50 {
            color: red;
            font-weight: bold;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-right: 10px;
        }
        input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php

use config\Config;

include_once __DIR__ . '/../db/Database.php';

class Table {
    private Database $db;
    private int $minAge;

    public function __construct(Database $db, int $minAge = 0) {
        $this->db = $db;
        $this->minAge = $minAge;
    }

    private function getTableRows(): array {
        $sql = "SELECT * FROM `name` WHERE age >= ?";
        $params = array('types' => 'i', 'values' => array($this->minAge));
        return $this->db->executeSQL($sql, $params);
    }

    private function displayTable() {
        $tableRows = $this->getTableRows();
        echo "<table>";
        echo "<tr><th>ID</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Возраст</th><th>Действия</th></tr>";
        foreach ($tableRows as $row) {
            $ageClass = $row['age'] > 50 ? 'age-over-50' : '';
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['last_name']}</td>";
            echo "<td>{$row['first_name']}</td>";
            echo "<td>{$row['middle_name']}</td>";
            echo "<td class=\"{$ageClass}\">{$row['age']}</td>";
            echo "<td><a href='DeletePage.php?id={$row['id']}'>Удалить</a></td>";
            echo "<td><a href='insertRecord.php?id={$row['id']}'>Добавить еще</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->displayTable();
        }
    }
}


$db = new Database(new Config('config.ini'));
$minAge = filter_input(INPUT_GET, 'minAge', FILTER_VALIDATE_INT) ?? 0;
$userTable = new UserTable($db, $minAge);
$userTable->handleRequest();
?>
<form action="" method="get">
    <label for="minAge">Минимальный возраст:</label>
    <input type="number" id="minAge" name="minAge" value="<?= $minAge ?>">
    <input type="submit" value="Фильтровать">
</form>
<?php
displayTable($db, $minAge);
?>
</body>
</html>