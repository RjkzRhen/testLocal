<?php

use config\Config;


require_once __DIR__ . '/../config/Config.php';
include_once __DIR__ . '/../forms/InsertForm.php';
$config = new Config('config.ini');
$db = new Database($config);
class Database {
    public ?mysqli $conn;

    private Config $config;

    public function __construct(Config $config) {
        $this->config = $config;
        $this->conn = $this->getConnection();
    }

    private function getConnection() {
        $config = new Config('config.ini');

        $conn = new mysqli($this->config->getServername(), $this->config->getUsername(), $this->config->getPassword(), $this->config->getDbname());

        if ($conn->connect_error) {
            die("Ошибка подключения: " . $conn->connect_error);
        }

        return $conn;
    }

    public function executeSQL($sql, $params = null): false|mysqli_stmt
    {
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($params['types'], ...$params['values']);
        }
        $stmt->execute();
        return $stmt;
    }

    public function getTable(int $minAge): string {
        $rows = $this->getTableRows($minAge);
        $tableHtml = "<table>\n";
        $tableHtml .= "<tr><th>ID</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Возраст</th><th>Действия</th></tr>\n";
        foreach ($rows as $row) {
            $ageClass = $row['age'] > 50 ? 'age-over-50' : '';
            $tableHtml .= "<tr>\n";
            $tableHtml .= "<td>{$row['id']}</td>\n";
            $tableHtml .= "<td>{$row['last_name']}</td>\n";
            $tableHtml .= "<td>{$row['first_name']}</td>\n";
            $tableHtml .= "<td>{$row['middle_name']}</td>\n";
            $tableHtml .= "<td class='{$ageClass}'>{$row['age']}</td>\n";
            $tableHtml .= "<td><a href='?deleteId={$row['id']}'>Удалить</a></td>\n";
            $tableHtml .= "</tr>\n";
        }
        $tableHtml .= "</table>\n";
        return $tableHtml;
    }

    public function getTableRows(int $minAge): array {
        $sql = "SELECT * FROM `name` WHERE age >= ?";
        $params = array('types' => 'i', 'values' => array($minAge));
        $stmt = $this->executeSQL($sql, $params);
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function deleteRecord($id): void
    {
        $sql = "DELETE FROM `name` WHERE id = ?";
        $params = array('types' => 'i', 'values' => array($id));

        $stmt = $this->executeSQL($sql, $params);

        if ($stmt) {
            echo " ";
        } else {
            echo "Ошибка: " . $this->conn->error;
        }
    }

}