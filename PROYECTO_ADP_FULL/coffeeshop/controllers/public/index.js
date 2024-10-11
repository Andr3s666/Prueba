// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CATALOGO = SERVER + 'public/catalogo.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que muestra las categorías disponibles.
    readAllCategorias();
    // Se define una variable para establecer las opciones del componente Slider.
    let options = {
        height: 300
    }
    // Se inicializa el componente Slider para que funcione el carrusel de imágenes.
    M.Slider.init(document.querySelectorAll('.slider'), options);
});

// Función para obtener y mostrar las categorías disponibles.
function readAllCategorias() {
    // Petición para solicitar los datos de las categorías.
    fetch(API_CATALOGO + 'readAll', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es satisfactoria, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es correcta, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    let content = '';
                    let url = '';
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se define una dirección con los datos de cada categoría para mostrar sus productos en otra página web.
                        url = `articles.html?id=${row.id_categoria}&nombre=${row.nombre_categoria}`;
                        // Se crean y concatenan las tarjetas con los datos de cada categoría.
                        content += `
                            <div class="col s12 m6 l4">
                                <div class="card hoverable">
                                    <div class="card-image waves-effect waves-block waves-light">
                                        <img src="${SERVER}images/categorias/${row.imagen_categoria}" class="activator">
                                    </div>
                                    <div class="card-content">
                                        <span class="card-title activator grey-text text-darken-4">
                                            <b>${row.nombre_categoria}</b>
                                            <i class="material-icons right tooltipped" data-tooltip="Descripción">more_vert</i>
                                        </span>
                                        <p class="center">
                                            <a href="${url}" class="tooltipped" data-tooltip="Ver productos">
                                                <i class="material-icons">local_cafe</i>
                                            </a>
                                        </p>
                                    </div>
                                    <div class="card-reveal">
                                        <span class="card-title grey-text text-darken-4">
                                            <b>${row.nombre_categoria}</b>
                                            <i class="material-icons right tooltipped" data-tooltip="Cerrar">close</i>
                                        </span>
                                        <p>${row.descripcion_categoria}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    // Se agregan las tarjetas a la etiqueta div mediante su id para mostrar las categorías.
                    document.getElementById('categorias').innerHTML = content;
                    // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
                    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
                } else {
                    // Se asigna al título del contenido un mensaje de error cuando no existen datos para mostrar.
                    let title = `<i class="material-icons small">cloud_off</i><span class="red-text">${response.exception}</span>`;
                    document.getElementById('title').innerHTML = title;
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}