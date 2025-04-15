<?php
session_start();

require_once '../config/db.php';
require_once '../models/User.php';


// Validar si es admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/login.php");
    exit;
}


// Conexión
$db = new Db();
$conn = $db->getConnection();

$userModel = new User($conn);


if (isset($_POST['delete'])) {
    $userId = $_POST['user_id'];
    $success = $userModel->deleteUser($userId);

    if ($success) {
        $_SESSION['message'] = "Usuario eliminado correctamente.";

    } else {
        $_SESSION['error'] = "Error al eliminar el usuario.";
    }

    header("Location: ../views/users/user-management.php");
    exit;
}


if (isset($_POST['create'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];

    if ($userModel->createUser($username, $email, $password, $role_id)) {
        $_SESSION['message'] = "Usuario creado correctamente.";
        header("Location: ../views/users/user-management.php");
        exit;
    } else {
        $_SESSION['error'] = "Error al crear el usuario.";
        header("Location: ../views/users/create-user.php");
        exit;
    }

    header("Location: ../views/users/user-management.php");
    exit;
}

// EDITAR USUARIO - MOSTRAR FORMULARIO
if (isset($_GET['edit'])) {
    $userId = $_GET['edit'];
    $usuario = $userModel->getUserById($userId);

    // Obtener roles antes de mostrar el form
    $roles = [];
    $result = $conn->query("SELECT * FROM roles");
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }

    include __DIR__ . '/../views/users/edit-user.php';
    exit;
}


// ACTUALIZAR USUARIO
if (isset($_POST['update'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];

    $exito = $userModel->updateUser($id, $username, $email, $role_id);

    if ($exito) {
        if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'actualizado') {
            echo "<div class='alert alert-success'>Usuario actualizado con éxito.</div>";
        }
        
        header("Location: ../views/users/user-management.php?mensaje=actualizado");
    } else {
        echo "Error al actualizar el usuario.";
    }
    exit;
}