<?php
include_once '../db/Database.php';

class InsertRecord {

    private Database $db;
    public function __construct() {
        $this->db = new Database();
    }
    public function insertRecord($data) {
        $sql = "INSERT INTO `name` (last_name, first_name, middle_name, age) VALUES (?, ?, ?, ?)";
        $params = array('types' => 'sssi', 'values' => array($data['last_name'], $data['first_name'], $data['middle_name'], $data['age']));
        $stmt = $this->executeSQL($sql, $params);

        if ($stmt) {
            header("Location: index.php");
            exit;
        } else {
            echo "Ошибка при добавлении записи: " . $this->db->conn->error;
        }
    }
    private function executeSQL($sql, $params) {
        $stmt = $this->db->conn->prepare($sql) or die("Ошибка при подготовке запроса: " . $this->db->conn->error);

        if ($params) {
            $stmt->bind_param($params['types'], ...$params['values']);
        }

        $stmt->execute() or die("Ошибка при выполнении запроса: " . $stmt->error);

        return $stmt;
    }
    public function handleRequest() {
        if (isset($_POST['update'])) {
            $data = array(
                'id' => $_POST['id'],
                'last_name' => $_POST['last_name'],
                'first_name' => $_POST['first_name'],
                'middle_name' => $_POST['middle_name'],
                'age' => $_POST['age']
            );
            $this->insertRecord($data);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Таблица пользователей</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input:invalid {
            border: 1px solid red;
        }
    </style>
</head>
<body>
<form method="post">
    <label for="last_name">Фамилия:</label>
    <input type="text" id="last_name" name="last_name" required><br>
    <label for="first_name">Имя:</label>
    <input type="text" id="first_name" name="first_name" required><br>
    <label for="middle_name">Отчество:</label>
    <input type="text" id="middle_name" name="middle_name" required><br>
    <label for="age">Возраст:</label>
    <input type="number" id="age" name="age" required><br>
    <input type="submit" name="update" value="Обновить">
</form>
</body>
</html>

