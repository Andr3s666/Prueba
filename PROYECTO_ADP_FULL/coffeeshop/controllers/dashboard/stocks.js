// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_STOCKS = SERVER + 'dashboard/stocks.php?action=';
const API_PRODUCTOS = SERVER + 'dashboard/productos.php?action=readAll';
const API_COLORES = SERVER + 'dashboard/colores.php?action=readAll';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla.
    readRows(API_STOCKS);
    // Se define una variable para establecer las opciones del componente Modal.
    let options = {
        dismissible: false,
        onOpenStart: function () {
            // Se restauran los elementos del formulario.
            document.getElementById('save-form').reset();
        }
    }
    // Se inicializa el componente Modal para que funcionen las cajas de diálogo.
    M.Modal.init(document.querySelectorAll('.modal'), options);
});

// Función para llenar la tabla con los datos de los registros.
function fillTable(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila.
    dataset.forEach(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <tr>
                <td>${row.nombre_producto}</td>
                <td>${row.nombre_color}</td>
                <td>${row.cantidad}</td>
                <td>
                    <a onclick="openUpdate(${row.id_stock})" class="btn-floating waves-effect blue tooltipped" data-tooltip="Actualizar">
                        <i class="material-icons">mode_edit</i>
                    </a>
                    <a onclick="openDelete(${row.id_stock})" class="btn-floating waves-effect red tooltipped" data-tooltip="Eliminar">
                        <i class="material-icons">delete</i>
                    </a>
                </td>
            </tr>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla.
    document.getElementById('tbody-rows').innerHTML = content;
    // Se inicializa el componente Tooltip.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
};

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se llama a la función que realiza la búsqueda.
    searchRows(API_STOCKS, 'search-form');
});

// Función para preparar el formulario al momento de insertar un registro.
function openCreate() {
    // Se abre la caja de diálogo (modal) que contiene el formulario.
    M.Modal.getInstance(document.getElementById('save-modal')).open();
    // Se asigna el título para la caja de diálogo (modal).
    document.getElementById('modal-title').textContent = 'Crear stock';
    // Se llama a la función que llena el select de productos.
    fillSelect(API_PRODUCTOS, 'producto', null);
    // Se llama a la función que llena el select de colores.
    fillSelect(API_COLORES, 'color', null);
}

// Función para preparar el formulario al momento de modificar un registro.
function openUpdate(id) {
    // Se abre la caja de diálogo (modal) que contiene el formulario.
    M.Modal.getInstance(document.getElementById('save-modal')).open();
    // Se asigna el título para la caja de diálogo (modal).
    document.getElementById('modal-title').textContent = 'Actualizar stock';
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_STOCKS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById('id').value = response.dataset.id_stock;
                    fillSelect(API_PRODUCTOS, 'producto', response.dataset.id_producto);
                    fillSelect(API_COLORES, 'color', response.dataset.id_color);
                    document.getElementById('cantidad').value = response.dataset.cantidad;
                    // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
                    M.updateTextFields();
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de guardar.
document.getElementById('save-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se define una variable para establecer la acción a realizar en la API.
    const action = document.getElementById('id').value ? 'update' : 'create';
    // Se llama a la función para guardar el registro.
    saveRow(API_STOCKS, action, 'save-form', 'save-modal');
});

// Función para establecer el registro a eliminar y abrir una caja de diálogo de confirmación.
function openDelete(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Se llama a la función que elimina un registro.
    confirmDelete(API_STOCKS, data);
}
