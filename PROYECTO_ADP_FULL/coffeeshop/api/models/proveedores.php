<?php

class Proveedores extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $nombre_proveedor = null;
    private $contacto_proveedor = null;
    private $telefono_proveedor = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombreProveedor($value)
    {
        if ($this->validateAlphanumeric($value, 1, 100)) {
            $this->nombre_proveedor = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setContactoProveedor($value)
    {
        if ($this->validateEmail($value)) {
            $this->contacto_proveedor = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTelefonoProveedor($value)
    {
        if ($this->validatePhone($value)) { // Suponiendo que tienes un método validatePhone en Validator
            $this->telefono_proveedor = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId()
    {
        return $this->id;
    }

    public function getNombreProveedor()
    {
        return $this->nombre_proveedor;
    }

    public function getContactoProveedor()
    {
        return $this->contacto_proveedor;
    }

    public function getTelefonoProveedor()
    {
        return $this->telefono_proveedor;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT id_proveedor, nombre_proveedor, contacto_proveedor, telefono_proveedor
                FROM proveedores
                WHERE nombre_proveedor ILIKE ?
                ORDER BY nombre_proveedor';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO proveedores(nombre_proveedor, contacto_proveedor, telefono_proveedor)
                VALUES(?, ?, ?)';
        $params = array($this->nombre_proveedor, $this->contacto_proveedor, $this->telefono_proveedor);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_proveedor, nombre_proveedor, contacto_proveedor, telefono_proveedor
                FROM proveedores
                ORDER BY nombre_proveedor';
        return Database::getRows($sql, null);
    }

    public function readOne()
    {
        $sql = 'SELECT id_proveedor, nombre_proveedor, contacto_proveedor, telefono_proveedor
                FROM proveedores
                WHERE id_proveedor = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE proveedores
                SET nombre_proveedor = ?, contacto_proveedor = ?, telefono_proveedor = ?
                WHERE id_proveedor = ?';
        $params = array($this->nombre_proveedor, $this->contacto_proveedor, $this->telefono_proveedor, $this->id);
        return Database::executeRow($sql, $params);
    }
    
    public function deleteRow()
    {
        $sql = 'DELETE FROM proveedores
                WHERE id_proveedor = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
