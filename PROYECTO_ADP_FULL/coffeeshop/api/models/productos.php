<?php
class Productos extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $nombre = null;
    private $descripcion = null;
    private $precio = null;
    private $categoria = null; // Clave foránea de la tabla categorías
    private $talla = null; // Clave foránea de la tabla tallas

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

    public function setDescripcion($value)
    {
        if ($this->validateAlphanumeric($value, 1, 200)) {
            $this->descripcion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setPrecio($value)
    {
        if ($this->validateMoney($value)) {
            $this->precio = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCategoria($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->categoria = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTalla($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->talla = $value;
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

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function getTalla()
    {
        return $this->talla;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function createRow()
    {
        $sql = 'INSERT INTO producto(nombre_producto, descripcion_producto, precio, id_categoria, id_talla)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->descripcion, $this->precio, $this->categoria, $this->talla);
        return Database::executeRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE producto 
                SET nombre_producto = ?, descripcion_producto = ?, precio = ?, id_categoria = ?, id_talla = ? 
                WHERE id_producto = ?';
        $params = array($this->nombre, $this->descripcion, $this->precio, $this->categoria, $this->talla, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM producto WHERE id_producto = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT p.id_producto, p.nombre_producto, p.descripcion_producto, p.precio, c.nombre_categoria, t.descripcion_talla
                FROM producto p
                JOIN categoria c ON p.id_categoria = c.id_categoria
                JOIN talla t ON p.id_talla = t.id_talla
                ORDER BY p.nombre_producto';
        return Database::getRows($sql, null);
    }

    public function readOne()
    {
        $sql = 'SELECT id_producto, nombre_producto, descripcion_producto, precio AS precio_producto, id_categoria, id_talla
                FROM producto
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }    

    public function searchRows($value)
    {
        $sql = 'SELECT p.id_producto, p.nombre_producto, p.descripcion_producto, p.precio, c.nombre_categoria, t.descripcion_talla
                FROM producto p
                JOIN categoria c ON p.id_categoria = c.id_categoria
                JOIN talla t ON p.id_talla = t.id_talla
                WHERE p.nombre_producto ILIKE ?
                ORDER BY p.nombre_producto';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    public function cantidadProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, COUNT(id_producto) cantidad
        FROM producto INNER JOIN categoria USING(id_categoria)
        GROUP BY nombre_categoria ORDER BY cantidad DESC';
    $params = null;
    return Database::getRows($sql, $params);
}
    
    public function porcentajeProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, ROUND((COUNT(id_producto) * 100.0 / (SELECT COUNT(id_producto) FROM producto)), 2) porcentaje
                FROM producto INNER JOIN categoria USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY porcentaje DESC';
        $params = null;
        return Database::getRows($sql, $params);
    }
    
}
?>
