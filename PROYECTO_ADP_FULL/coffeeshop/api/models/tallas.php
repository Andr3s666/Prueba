<?php

class Tallas extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $descripcion = null; // Cambiado de nombre a descripcion

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

    public function setDescripcion($value) // Cambiado de setNombre a setDescripcion
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->descripcion = $value; // Cambiado de nombre a descripcion
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

    public function getDescripcion() // Cambiado de getNombre a getDescripcion
    {
        return $this->descripcion; // Cambiado de nombre a descripcion
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT id_talla, descripcion_talla
                FROM talla
                WHERE descripcion_talla ILIKE ?
                ORDER BY descripcion_talla';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO talla(descripcion_talla)
                VALUES(?)';
        $params = array($this->descripcion); 
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_talla, descripcion_talla
                FROM talla
                ORDER BY descripcion_talla';
        return Database::getRows($sql, null);
    }

    public function readOne()
    {
        $sql = 'SELECT id_talla, descripcion_talla
                FROM talla
                WHERE id_talla = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE talla SET descripcion_talla = ? WHERE id_talla = ?'; 
        $params = array($this->descripcion, $this->id); 
        return Database::executeRow($sql, $params);
    }
    
    public function deleteRow()
    {
        $sql = 'DELETE FROM talla
                WHERE id_talla = ?'; 
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
