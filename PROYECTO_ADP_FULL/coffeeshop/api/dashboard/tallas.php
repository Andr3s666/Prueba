<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/tallas.php'); 

if (isset($_GET['action'])) {
    session_start(); 
    $tallas = new Tallas; 
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    
    if (isset($_SESSION['id_usuario'])) {
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $tallas->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;

            case 'search':
                $_POST = $tallas->validateForm($_POST);
                if (empty($_POST['search'])) { 
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $tallas->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Talla encontrada';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;

            case 'create':
                $_POST = $tallas->validateForm($_POST);
                if (!$tallas->setDescripcion($_POST['descripcion'])) { 
                    $result['exception'] = 'Descripción incorrecta';
                } elseif ($tallas->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Talla creada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

            case 'readOne':
                if (!$tallas->setId($_POST['id'])) {
                    $result['exception'] = 'Talla incorrecta';
                } elseif ($result['dataset'] = $tallas->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Talla inexistente';
                }
                break;

            case 'update':
                $_POST = $tallas->validateForm($_POST);
                if (!$tallas->setId($_POST['id'])) {
                    $result['exception'] = 'Talla incorrecta';
                } elseif (!$data = $tallas->readOne()) {
                    $result['exception'] = 'Talla inexistente';
                } elseif (!$tallas->setDescripcion($_POST['descripcion'])) { 
                    $result['exception'] = 'Descripción incorrecta';
                } elseif ($tallas->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Talla modificada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

            case 'delete':
                if (!$tallas->setId($_POST['id'])) {
                    $result['exception'] = 'Talla incorrecta';
                } elseif (!$data = $tallas->readOne()) {
                    $result['exception'] = 'Talla inexistente';
                } elseif ($tallas->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Talla eliminada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }

        header('Content-Type: application/json; charset=utf-8');
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
