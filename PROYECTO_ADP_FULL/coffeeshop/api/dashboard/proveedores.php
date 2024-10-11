<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/proveedores.php');

// Se comprueba si existe una acción a realizar.
if (isset($_GET['action'])) {
    session_start(); // Asegúrate de que esto se llama antes de cualquier salida.
    $proveedores = new Proveedores;
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    
    // Verifica si hay sesión iniciada
    if (isset($_SESSION['id_usuario'])) { // Cambiado de id_proveedor a id_usuario
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $proveedores->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;

            case 'search':
                $_POST = $proveedores->validateForm($_POST);
                if (empty($_POST['search'])) { // Usar empty para una mejor legibilidad
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $proveedores->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Proveedor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;

            case 'create':
                $_POST = $proveedores->validateForm($_POST);
                if (!$proveedores->setNombreProveedor($_POST['nombre_proveedor'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$proveedores->setContactoProveedor($_POST['contacto_proveedor'])) {
                    $result['exception'] = 'Contacto incorrecto';
                } elseif (!$proveedores->setTelefonoProveedor($_POST['telefono_proveedor'])) {
                    $result['exception'] = 'Teléfono incorrecto';
                } elseif ($proveedores->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Proveedor creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

            case 'readOne':
                if (!$proveedores->setId($_POST['id'])) {
                    $result['exception'] = 'Proveedor incorrecto';
                } elseif ($result['dataset'] = $proveedores->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Proveedor inexistente';
                }
                break;

            case 'update':
                $_POST = $proveedores->validateForm($_POST);
                if (!$proveedores->setId($_POST['id'])) {
                    $result['exception'] = 'Proveedor incorrecto';
                } elseif (!$data = $proveedores->readOne()) {
                    $result['exception'] = 'Proveedor inexistente';
                } elseif (!$proveedores->setNombreProveedor($_POST['nombre_proveedor'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$proveedores->setContactoProveedor($_POST['contacto_proveedor'])) {
                    $result['exception'] = 'Contacto incorrecto';
                } elseif (!$proveedores->setTelefonoProveedor($_POST['telefono_proveedor'])) {
                    $result['exception'] = 'Teléfono incorrecto';
                } elseif ($proveedores->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Proveedor modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

            case 'delete':
                if (!$proveedores->setId($_POST['id'])) {
                    $result['exception'] = 'Proveedor incorrecto';
                } elseif (!$data = $proveedores->readOne()) {
                    $result['exception'] = 'Proveedor inexistente';
                } elseif ($proveedores->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Proveedor eliminado correctamente';
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
