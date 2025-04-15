<?php
require_once __DIR__ . '/../config/db.php';



class User {
    public $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    // LOGIN
    public function login($username, $password) {
        $stmt = $this->conn->prepare("
            SELECT users.*, roles.name as role 
            FROM users 
            INNER JOIN roles ON users.role_id = roles.id
            WHERE users.username = ?
        ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
    
        return false;
    }
    
    //validar usuario
    public function usuarioExiste($username) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
    
    //crear usuario
    public function createUser($username, $email, $password, $role_id) { 
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $email, $hashedPassword, $role_id);
        return $stmt->execute();
    }

    

    public function emailExiste($email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
    
        return $stmt->num_rows > 0;
    }
    
    

    //  OBTENER TODOS LOS USUARIOS  public function getAllUsers() {
        public function getAllUsersWithRoles() {
            $sql = "SELECT users.id, users.username, users.email, roles.name AS role_name 
                    FROM users 
                    JOIN roles ON users.role_id = roles.id";
            $result = $this->conn->query($sql);
        
            $usuarios = [];
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        
            return $usuarios;
        }
        

    // ELIMINAR USUARIO
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function updateUser($id, $username, $email, $role_id) {
        $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ?, role_id = ? WHERE id = ?");
        $stmt->bind_param("ssii", $username, $email, $role_id, $id);
        return $stmt->execute();
    }
    
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    

}