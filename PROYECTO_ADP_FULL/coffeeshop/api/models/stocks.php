<?php
class Stocks extends Validator
{
    private $id = null;
    private $id_producto = null;
    private $id_color = null;
    private $cantidad = null;

    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdProducto($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdColor($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_color = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCantidad($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->cantidad = $value;
            return true;
        } else {
            return false;
        }
    }

    public function createRow()
    {
        $sql = 'INSERT INTO stock(id_producto, id_color, cantidad) VALUES(?, ?, ?)';
        $params = array($this->id_producto, $this->id_color, $this->cantidad);
        return Database::executeRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE stock SET id_producto = ?, id_color = ?, cantidad = ? WHERE id_stock = ?';
        $params = array($this->id_producto, $this->id_color, $this->cantidad, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM stock WHERE id_stock = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT s.id_stock, s.id_producto, s.id_color, s.cantidad, p.nombre_producto, c.nombre_color 
                FROM stock s 
                JOIN producto p ON s.id_producto = p.id_producto 
                JOIN color c ON s.id_color = c.id_color 
                ORDER BY s.id_stock';
        return Database::getRows($sql, null);
    }

    public function readOne()
    {
        $sql = 'SELECT id_stock, id_producto, id_color, cantidad FROM stock WHERE id_stock = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
}
?>
