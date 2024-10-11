<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/stocks.php');

if (isset($_GET['action'])) {
    session_start();
    $stock = new Stocks;
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    if (isset($_SESSION['id_usuario'])) {
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $stock->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'create':
                $_POST = $stock->validateForm($_POST);
                if (!$stock->setIdProducto($_POST['producto'])) {
                    $result['exception'] = 'Producto incorrecto';
                } elseif (!$stock->setIdColor($_POST['color'])) {
                    $result['exception'] = 'Color incorrecto';
                } elseif (!$stock->setCantidad($_POST['cantidad'])) {
                    $result['exception'] = 'Cantidad incorrecta';
                } elseif ($stock->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Stock creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readOne':
                if (!$stock->setId($_POST['id'])) {
                    $result['exception'] = 'Stock incorrecto';
                } elseif ($result['dataset'] = $stock->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Stock inexistente';
                }
                break;
            case 'update':
                $_POST = $stock->validateForm($_POST);
                if (!$stock->setId($_POST['id'])) {
                    $result['exception'] = 'Stock incorrecto';
                } elseif (!$data = $stock->readOne()) {
                    $result['exception'] = 'Stock inexistente';
                } elseif (!$stock->setIdProducto($_POST['producto'])) {
                    $result['exception'] = 'Producto incorrecto';
                } elseif (!$stock->setIdColor($_POST['color'])) {
                    $result['exception'] = 'Color incorrecto';
                } elseif (!$stock->setCantidad($_POST['cantidad'])) {
                    $result['exception'] = 'Cantidad incorrecta';
                } elseif ($stock->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Stock modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if (!$stock->setId($_POST['id'])) {
                    $result['exception'] = 'Stock incorrecto';
                } elseif (!$data = $stock->readOne()) {
                    $result['exception'] = 'Stock inexistente';
                } elseif ($stock->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Stock eliminado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
        header('content-type: application/json; charset=utf-8');
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
?>
