<?php

use config\Config;

require_once '../config/Config.php';

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


    public function executeSQL($sql, $params = null) {
        $stmt = $this->conn->prepare($sql) or die("Ошибка при подготовке запроса: " . $this->conn->error);

        if ($params) {
            $stmt->bind_param($params['types'], ...$params['values']);
        }

        $stmt->execute() or die("Ошибка при выполнении запроса: " . $stmt->error);

        return $stmt;
    }

    public function closeConnection() {
        $this->conn->close();
    }

    public function deleteRecord($id) {
        $sql = "DELETE FROM `name` WHERE id = ?";
        $params = array('types' => 'i', 'values' => array($id));

        $stmt = $this->executeSQL($sql, $params);

        if ($stmt) {
            echo "Запись успешно удалена.";
        } else {
            echo "Ошибка: " . $this->conn->error;
        }
    }

    public function insertRecord($data) {
        $sql = "INSERT INTO `name` (last_name, first_name, middle_name, age) VALUES (?, ?, ?, ?)";
        $params = array('types' => 'sssi', 'values' => array($data['last_name'], $data['first_name'], $data['middle_name'], $data['age']));

        $stmt = $this->executeSQL($sql, $params);

        if ($stmt) {
            echo "Новая запись успешно добавлена.";
        } else {
            echo "Ошибка при добавлении записи: " . $this->conn->error;
        }
    }
    public function getTableRows($minAge = 0): array
    {
        $sql = "SELECT * FROM `name` WHERE age >= ?";
        $params = array('types' => 'i', 'values' => array($minAge));
        $stmt = $this->executeSQL($sql, $params);
        $rows = array();
        if ($stmt && $result = $stmt->get_result()) {
            while ($row = $result->fetch_assoc()) {
                $ageClass = $row['age'] > 50 ? 'age-over-50' : '';
                $rows[] = array(
                    'id' => $row['id'],
                    'last_name' => $row['last_name'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'age' => $row['age'],
                    'ageClass' => $ageClass
                );
            }
        }
        return $rows;
    }
}
?>