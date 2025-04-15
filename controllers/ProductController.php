<?php
session_start();
require_once '../config/db.php';
require_once '../models/Product.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class ProductController {
    private $productModel;

    public function __construct() {
        $db = new Db();
        $conn = $db->getConnection();
        $this->productModel = new Product($conn);
    }

    // Agregar producto
    public function store($nombre, $descripcion, $precio, $imagen, $categoria_id) {
        if ($this->productModel->add($nombre, $descripcion, $precio, $imagen, $categoria_id)) {
            $_SESSION['message'] = "Producto creado correctamente.";
        } else {
            $_SESSION['message'] = "Error al crear el producto.";
        }
        header("Location: ../views/products/index.php");
        exit;
    }


    //Eliminar producto
    public function delete($id) {
        if ($this->productModel->delete($id)) {
            $_SESSION['message'] = "Producto eliminado correctamente.";
        } else {
            $_SESSION['message'] = "Error al eliminar el producto.";
        }
        header("Location: ../views/products/index.php");
        exit;
    }

    //actualizar producto
    public function update($id, $name, $description, $price, $image, $category_id) {
        if ($this->productModel->update($id, $name, $description, $price, $image, $category_id)) {
            $_SESSION['message'] = "Producto actualizado correctamente.";
        } else {
            $_SESSION['message'] = "Error al actualizar el producto.";
        }
        header("Location: ../views/products/index.php");
        exit;
    }
    
       
}

//peticiones post

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ProductController();

    $imagen = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $nombreTmp = $_FILES['image']['tmp_name'];
        $nombreFinal = uniqid() . '_' . $_FILES['image']['name'];
        move_uploaded_file($nombreTmp, '../uploads/' . $nombreFinal);
        $imagen = $nombreFinal;
    }
    

    if ($_POST['action'] === 'add') {
        $controller->store(
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $imagen,
            $_POST['category_id']
        );
    }

    if ($_POST['action'] === 'update') {
        $controller->update(
            $_POST['id'],
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $imagen,
            $_POST['category_id']
        );
    }
}

  // peticion eliminar producto
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $controller = new ProductController();
    $controller->delete($_GET['id']);
    exit;
}