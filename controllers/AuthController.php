<?php
// Mostrar errores en pantalla
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Incluir dependencias
require_once '../config/db.php';
require_once '../models/User.php';

class AuthController {
    private $user;

    public function __construct() {
        $db = new Db();
        $conn = $db->getConnection();
        $this->user = new User($conn); 
    }

    public function login($username, $password) {
        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = "Completa todos los campos.";
            header("Location: ../views/login.php");
            exit;
        }

        $userData = $this->user->login($username, $password);

        if ($userData) {
            $_SESSION['username'] = $userData['username'];
            $_SESSION['role'] = $userData['role'];
            $_SESSION['user_id'] = $userData['id'];
            header("Location: ../views/dashboard.php");
        } else {
            $_SESSION['login_error'] = "Credenciales incorrectas.";
            header("Location: ../views/login.php");
        }
        exit;
    }

    public function register($username, $email, $password) {
       
        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['register_error'] = "Completa todos los campos.";
            $_SESSION['old_input'] = [
                'username' => $username,
                'email' => $email
            ];
            header("Location: ../views/register.php");
            exit;
        }
        
    
        if ($this->user->usuarioExiste($username)) {
            $_SESSION['register_error'] = "El nombre de usuario ya existe.";
            $_SESSION['old_input'] = [
                'username' => $username,
                'email' => $email
            ];
            header("Location: ../views/register.php");
            exit;
        }
        
    
        if ($this->user->emailExiste($email)) {
            $_SESSION['register_error'] = "El correo electrónico ya está registrado.";
            header("Location: ../views/register.php");
            exit;
        }
        $password = $_POST['password']; // sin hashear aquí

        if ($this->user->createUser($username, $email, $password, 2)) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'cliente';
            header("Location: ../views/dashboard.php");
        } else {
            $_SESSION['register_error'] = "Error al registrar el usuario.";
            header("Location: ../views/register.php");
        }
    
        exit;
    }
    
    



}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();

    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $auth->login(trim($_POST['username']), trim($_POST['password']));
    }

    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $auth->register(trim($_POST['username']), trim($_POST['email']), trim($_POST['password']));
    }
    
}