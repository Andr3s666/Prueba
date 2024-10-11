<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/colores.php');

// Se comprueba si existe una acción a realizar.
if (isset($_GET['action'])) {
    session_start(); // Asegúrate de que esto se llama antes de cualquier salida.
    $colores = new Colores;
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    
    // Verifica si hay sesión iniciada
    if (isset($_SESSION['id_usuario'])) { // Cambiado de id_color a id_usuario
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $colores->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;

            case 'search':
                $_POST = $colores->validateForm($_POST);
                if (empty($_POST['search'])) { // Usar empty para una mejor legibilidad
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $colores->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Color encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;

            case 'create':
                $_POST = $colores->validateForm($_POST);
                if (!$colores->setNombre($_POST['nombre'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif ($colores->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Color creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

            case 'readOne':
                if (!$colores->setId($_POST['id'])) {
                    $result['exception'] = 'Color incorrecto';
                } elseif ($result['dataset'] = $colores->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Color inexistente';
                }
                break;

            case 'update':
                $_POST = $colores->validateForm($_POST);
                if (!$colores->setId($_POST['id'])) {
                    $result['exception'] = 'Color incorrecto';
                } elseif (!$data = $colores->readOne()) {
                    $result['exception'] = 'Color inexistente';
                } elseif (!$colores->setNombre($_POST['nombre'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif ($colores->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Color modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

            case 'delete':
                if (!$colores->setId($_POST['id'])) {
                    $result['exception'] = 'Color incorrecto';
                } elseif (!$data = $colores->readOne()) {
                    $result['exception'] = 'Color inexistente';
                } elseif ($colores->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Color eliminado correctamente';
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
