<?php
class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($category_id = null) {
        $query = "SELECT * FROM products";
        if ($category_id) {
            $query .= " WHERE category_id = ?";
        }
    
        $stmt = $this->conn->prepare($query);
        if ($category_id) {
            $stmt->bind_param("i", $category_id);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
    
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    
        return $productos;
    }
    

    public function add($nombre, $descripcion, $precio, $imagen, $categoria_id) {
        $sql = "INSERT INTO products (name, description, price, image, category_id) 
                VALUES (?, ?, ?, ?, ?)";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagen, $categoria_id);
    
        return $stmt->execute();
    }
    

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    

    public function update($id, $name, $description, $price, $image, $category_id) {
        if ($image) {
            $sql = "UPDATE products SET name = ?, description = ?, price = ?, image = ?, category_id = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$name, $description, $price, $image, $category_id, $id]);
        } else {
            $sql = "UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$name, $description, $price, $category_id, $id]);
        }
    }
    
    
    public function getByCategory($categoryId) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE category_id = ?");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    
        return $productos;
    }
    
}