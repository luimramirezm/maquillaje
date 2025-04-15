<?php
class Category {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    //obtener todas las categorias
    public function getAll() {
        $query = "SELECT * FROM categories";
        $result = $this->conn->query($query);
        $categories = [];

        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        return $categories;
    }
}
?>