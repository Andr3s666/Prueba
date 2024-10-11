/*
*   Controlador de uso general en las páginas web del sitio privado cuando se ha iniciado sesión.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para establecer la ruta y parámetros de comunicación con la API.
const API = SERVER + 'dashboard/usuarios.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    fetch(API + 'getUser', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se revisa si el usuario está autenticado, de lo contrario se envía a iniciar sesión.
                if (response.session) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se direcciona a la página web principal.
                    if (response.status) {
                        const header = `
                            <div class="navbar-fixed">
                                <nav class="black">
                                    <div class="nav-wrapper">
                                        <a href="main.html" class="brand-logo">
                                        <i class="material-icons">home</i>
                                        </a>
                                        <a href="#" data-target="mobile-menu" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                                        <ul class="right hide-on-med-and-down">        
                                            <li><a href="stocks.html"><i class="material-icons left">loyalty</i>Stock</a></li>
                                            <li><a href="productos.html"><i class="material-icons left">shop</i>Productos</a></li>
                                            <li><a href="categorias.html"><i class="material-icons left">shop_two</i>Categorías</a></li>
                                            <li><a href="colores.html"><i class="material-icons left">art_track</i>Colores</a></li>
                                            <li><a href="proveedores.html"><i class="material-icons left">contacts</i>Proveedores</a></li>
                                            <li><a href="tallas.html"><i class="material-icons left">loupe</i>Tallas</a></li>
                                            <li><a href="usuarios.html"><i class="material-icons left">group</i>Usuarios</a></li>
                                            <li>
                                                <a href="#" class="dropdown-trigger" data-target="desktop-dropdown">
                                                    <i class="material-icons left">verified_user</i>Cuenta: <b>${response.username}</b>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                                <ul id="desktop-dropdown" class="dropdown-content">
                                    <li><a href="profile.html"><i class="material-icons">face</i>Editar perfil</a></li>
                                    <li><a href="password.html"><i class="material-icons">lock</i>Cambiar clave</a></li>
                                    <li><a onclick="logOut()"><i class="material-icons">clear</i>Salir</a></li>
                                </ul>
                            </div>
                            <ul id="mobile-menu" class="sidenav">
                                <li><a href="stocks.html"><i class="material-icons">loyalty</i>Stock</a></li>
                                <li><a href="productos.html"><i class="material-icons">shop</i>Productos</a></li>
                                <li><a href="categorias.html"><i class="material-icons">shop_two</i>Categorías</a></li>
                                <li><a href="colores.html"><i class="material-icons">art_track</i>Colores</a></li>
                                <li><a href="proveedores.html"><i class="material-icons">contacts</i>Proveedores</a></li>
                                <li><a href="tallas.html"><i class="material-icons">loupe</i>Tallas</a></li>
                                <li><a href="usuarios.html"><i class="material-icons">group</i>Usuarios</a></li>
                                <li>
                                    <a class="dropdown-trigger" href="#" data-target="mobile-dropdown">
                                        <i class="material-icons">verified_user</i>Cuenta: <b>${response.username}</b>
                                    </a>
                                </li>
                            </ul>
                            <ul id="mobile-dropdown" class="dropdown-content">
                                <li><a href="profile.html">Editar perfil</a></li>
                                <li><a href="password.html">Cambiar clave</a></li>
                                <li><a onclick="logOut()">Salir</a></li>
                            </ul>
                        `;
                        const footer = `
                            <div class="container">
                                <div class="row">
                                    <div class="col s12 m6">
                                        <h6 class="white-text">Dashboard</h6>
                                        <a class="white-text" href="mailto:dacasoft@outlook.com">
                                            <i class="material-icons left">email</i>Ayuda
                                        </a>
                                    </div>
                                    <div class="col s12 m6">
                                        <h6 class="white-text">Enlaces</h6>
                                        <a class="white-text" href="../public/" target="_blank">
                                            <i class="material-icons left">store</i>Sitio público
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="footer-copyright">
                                <div class="container">
                                    <span>© 2018-2022 Copyright WolkInCloset. Todos los derechos reservados.</span>
                                    <span class="right">Diseñado con
                                        <a href="http://materializecss.com/" target="_blank">
                                            <img src="../../resources/img/materialize.png" height="20" style="vertical-align:middle" alt="Materialize">
                                        </a>
                                    </span>
                                </div>
                            </div>
                        `;
                        document.querySelector('header').innerHTML = header;
                        document.querySelector('footer').innerHTML = footer;
                        // Se inicializa el componente Dropdown para que funcione la lista desplegable en los menús.
                        M.Dropdown.init(document.querySelectorAll('.dropdown-trigger'));
                        // Se inicializa el componente Sidenav para que funcione la navegación lateral.
                        M.Sidenav.init(document.querySelectorAll('.sidenav'));
                    } else {
                        sweetAlert(3, response.exception, 'index.html');
                    }
                } else {
                    location.href = 'index.html';
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});