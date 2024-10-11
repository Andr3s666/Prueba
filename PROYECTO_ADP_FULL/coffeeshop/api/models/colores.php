<?php

class Colores extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $nombre = null;

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

    public function setNombre($value)
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->nombre = $value;
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

    public function getNombre()
    {
        return $this->nombre;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT id_color, nombre_color
                FROM color
                WHERE nombre_color ILIKE ?
                ORDER BY nombre_color';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO color(nombre_color)
                VALUES(?)';
        $params = array($this->nombre);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_color, nombre_color
                FROM color
                ORDER BY nombre_color';
        return Database::getRows($sql, null);
    }

    public function readOne()
    {
        $sql = 'SELECT id_color, nombre_color
                FROM color
                WHERE id_color = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE color SET nombre_color = ? WHERE id_color = ?'; // Cambiar a minúscula en "color"
        $params = array($this->nombre, $this->id); // Cambiar a $this->nombre y $this->id
        return Database::executeRow($sql, $params);
    }
    
    public function deleteRow()
    {
        $sql = 'DELETE FROM color
                WHERE id_color = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
