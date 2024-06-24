<?php
include_once '../db/Database.php';
include_once '../forms/InsertForm.php';
class DatabaseManager {
    private Database $db;
    private InsertForm $insertForm;
    public function __construct() {
        $this->db = new Database();
        $this->insertForm = new InsertForm();
    }

    public function getTableRows($minAge = 0) {
        $sql = "SELECT * FROM `name` WHERE age >= ?";
        $params = array('types' => 'i', 'values' => array($minAge));
        return $this->db->executeSQL($sql, $params);
    }

    public function deleteRecord($id) {
        $sql = "DELETE FROM `name` WHERE id = ?";
        $params = array('types' => 'i', 'values' => array($id));
        return $this->db->executeSQL($sql, $params);
    }

    public function handleInsertForm() {
        if (isset($_POST['submit'])) {
            $this->insertForm->handleRequest();
        }
    }
}
?>
