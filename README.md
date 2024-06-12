# MiraCasa
Autor: Francisco Montés Doria.  
Tipo: Proyecto final de programación.  
Grado: 1er Curso Grado Superior Desarrollo de Aplicaciones Web.  
Centro: IES L'Estació.

# Índice
- [MiraCasa](#miracasa)
- [Introducción](#introducción)
- [Contenido](#contenido)
  - [Home](#home)
  - [Search](#search)
  - [Shop](#shop)
  - [Details](#details)
  - [Login](#login)
  - [Cart](#cart)
  - [Profile](#profile)
- [Tecnologías](#tecnologías)
- [Librerías y APIs](#librerías-y-apis)

# Introducción
MiraCasa es una aplicación web para la compra, venta y alquiler de todo tipo de inmuebles, focalizada en las diferentes zonas turísticas de costa alrededor de España. Ofrece al usuario herramientas de búsqueda, y a través de su perfil, gestión, y posterior compra/alquiler de inmuebles.

# Contenido
La aplicación está comuesta por los siguientes módulos:

## 1️⃣ __Home__
El módulo de bienvenida y acceso a la aplicación web. Compuesto por varios carrouseles divididos por temáticas. El usuario puede interactuar con ellos con un clic y lo redirigen al Shop con la búsqueda segmentada.

| Carrusel | Descripción |
| ------- | --------------- |
| Zonas turísticas | Carrusel principal de inmuebles por zonas turísticas de costa |
| Tipos de inmuebles | Carrusel para los distintos tipos de inmuebles (viviendas, garajes, trasteros, oficinas, ...) |
| Categorías | Carrusel que clasifica los inmuebles por categorias (de diseño, de lujo, en la playa, ...) |
| Transacciones | Carrusel que muestra todas las operaciones comerciales disponibles |
| Ciudades | Carrusel con las principales ciudades de España |
| Última búsqueda | Carrusel que recupera los inmuebles de la última búsqueda realizada |
| Mas visitados | Carrusel que muestra los inmuebles con mas visitas |
| Recomendaciones | Carrusel que recomienda mas tipos de inmuebles |

## 2️⃣ __Search__
El módulo para la búsqueda de inmuebles, accesible desde cualquier parte de la aplicación web desde la barra de menú. Basa las búsquedas en tres criterios encadenados. Cualquier búsqueda seleccionada redirige al usuario al Shop para la visualización de los inmuebles.

| Criterio | Descripción |
| ------- | --------------- |
| Transacción | Tipos de operaciones comerciales (compra, alquiler, ...) |
| Zona turística | Zonas turísticas de costa de España |
| Ciudad | Todas la ciudades registradas según los inmuebles disponibles |


## 3️⃣ __Shop__
El módulo donde se muestra el listado de inmuebles disponibles.

| Características | Descripción |
| ------- | --------------- |
| Listado de inmuebles | Listado con la descripción de las características generales de los inmuebles, con carrusel incorporado con sus diferentes imágenes. Con paginación. |
| Filtros | Diferentes filtros para segmentar la búsqueda de inmuebles (tipos, categorías, extras, precio, habitaciones, ordenación). Estos enlazan con las búsquedas del search. Se pueden reiniciar de forma individual o completa. |
| Mapa | Mapa interactivo donde se visualiza la ubicación de los diferentes inmuebles según su localización. Cada ubicación con un clic abre un popup que puede redireccionar al Details |
| Likes | El usuario puede dar Likes a los inmuebles, pudiendo visualizar la totalidad que han recibido por parte de todos. También pueden dar Dislikes |
| Carrito | El usuario puede incluir cualquier inmueble disponible en el carrito, para su posterior compra/alquiler |

## 4️⃣ __Details__
Parte del módulo del Shop, este ofrece los detalles de cada inmueble. Principalmente el usuario accede a el haciendo clic sobre cualquier inmueble desde el Shop.

| Características | Descripción |
| ------- | --------------- |
| Detalles | Todos los detalles del inmueble (precio, localización, dimensiones, habitaciones, extras, descripción, ...)  |
| Like | El usuario puede dar Like al inmueble, visualizando también el total que ha recibido ese inmueble. También puede dar un Dislike |
| Carrito | El usuario puede incluir el inmueble en el carrito, para su posterior compra/alquiler |
| Localización | A través de un mapa interactivo, muestra la localización exacta del inmueble |
| Recomendaciones relacionadas | Recomendaciones extras de inmuebles relacionadas con el actual |

## 5️⃣ __Login__
El módulo para regristro, inicio de sesión de usuarios. El usuario accede a el a través de un pequeño modal desplegable desde la barra de menú. Una vez logeado el usuario, la aplicación muestra su nombre y avatar.

| Características | Descripción |
| ------- | --------------- |
| Registro | El usuario puede registrarse en la aplicación introduciendo sus datos (nombre, contraseña, email, teléfono). La aplicación verificará en dos pasos el email y el teléfono enviando un correo electrónico y un mensaje al whatsapp. Estos tienen tiempo de expiración. Sin la verificación el registro no se completa. |
| Login | Una vez registrado en la aplicación, el usuario puede iniciar sesión con su nombre de usuario y contraseña. Se ha añadido una capa de seguridad que se activa al fallar 3 veces consecutivas al introducir la contraseña. Para ello el usuario necesita introducir un código que se le envia a su whatsapp, que tiene tiempo de expiración. Tambíen está limitado a 3 intentos. Si falla se desactiva la cuenta del usuario. Podría recuperar la cuenta desde la recuperación |
| Social login | El usuario también puede acceder sin necesidad de registro a través de cuentas de Google y Github |
| Recuperación de cuenta | Cualquier usuario con la cuenta deshabilitada o que haya olvidado su contraseña, puede recuperar el acceso.  Solo necesita introducir el email con el que se registró y cumplir con la verificación en dos pasos (verificación de email, y introducción del código enviado al whatsapp) |
| Seguridad | La aplicación utiliza la tecnología JWT mas las cookies de sesión para evitar el robo de identidad. También controla la inactividad del usuario con deslogueos automáticos |

## 6️⃣ __Cart__
El módulo donde el usuario puede añadir inmuebles para su posterior compra. El usuario accede a el desde la barra de menú. Si no está logeado, al hace clic sobre el carrito, la aplicación le redirigirá al login. Si un carrito no se completa con una compra, siempre podrá volver a acceder a el en una posterior sesión de inicio.

| Características | Descripción |
| ------- | --------------- |
| Adición de inmuebles | El usuario puede añadir inmuebles al carrito, principalmente desde el Shop y el Details |
| Vista de detalles | Dentro del carrito el usuario puede ver la lista de inmuebles que ha añadido, con sus correspondientes detalles y cantidades |
| Eliminación de inmuebles | El usuario puede eliminar cualquier inmueble añadido de la lista del carrito |
| Modificación de cantidades | El usuario puede modificar las cantidades del inmueble a comprar, limitando el total al stock disponible |
| Cálculo de importe | La aplicación recalcula cualquier modificación sobre el carrito |
| Generación de factura | Cuando el usuario realiza la compra a través del botón disponible, la aplicación genera la factura en formato pdf |
| Generación de QR | Tras la compra, la aplicación también genera un códgigo QR para la descarga de la factura |

## 7️⃣ __Profile__
El módulo de acceso a los datos de cada usuario. Accesible, al igual que el login, desde el modal desplegable en la barra de menú. Si el usuario no está logeado, al hacer clic sobre el profile, la aplicación le redirigirá al login.

| Características | Descripción |
| ------- | --------------- |
| Mi cuenta | Apartado predeterminado cuando se accede al perfil. El usuario puede visualizar su información básica de su cuenta. También podrá actualizar sus datos (nombre, email, teléfono y avatar). El nombre de usuario y email que el usuario quiera modificar, no podrá coincidir nunca con cualquiera dado de alta en la aplicación |
| Mis compras | Apartado donde el usuario podrá acceder a las facturas y QRs generados en cada compra realizada |
| Mis likes | El usuario podrá ver listadas todas las viviendas a las que le ha dado Like. Podrá eliminarlas de la lista, incluirlas en el carrito de la compra, y acceder al Details de las mismas |

# Tecnologías
- Frontend: &nbsp;
 ![Jquery](https://img.shields.io/badge/jquery-007ACC?style=for-the-badge&logo=jquery&logoColor=white)
 ![JavaScript](https://img.shields.io/badge/javascript-FFEE58?style=for-the-badge&logo=javascript&logoColor=white)
- Backend: &nbsp;
![PHP](https://img.shields.io/badge/php-787CB5?style=for-the-badge&logo=php&logoColor=white) 
![JWT](https://img.shields.io/badge/jwt-323330?style=for-the-badge&logo=json-web-tokens&logoColor=pink)
- Bases de datos: &nbsp;
![MySQL](https://img.shields.io/badge/mysql-FF8C00?style=for-the-badge&logo=mysql&logoColor=white)
- Diseño: &nbsp;
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
- IDE: &nbsp;
![Visual Studio Code](https://img.shields.io/badge/Visual_Studio_Code-0078D4?style=for-the-badge&logo=visual%20studio%20code&logoColor=white)


# Librerías y APIs
- [Mapbox GL JS](https://www.mapbox.com/): Libreria JavaScript que utiliza WebGL para renderizar mapas interactivos.
- [Firebase](https://console.firebase.google.com/): API para social login.
- [Resend](https://resend.com/): API para el envío de emails por lotes.
- [Ultramsg](https://ultramsg.com/): API para el envío de WhatsApps.
- [Dompdf](https://github.com/dompdf/dompdf): Librería en php para la conversión de html en pdf.
- [PHP QR Code](https://phpqrcode.sourceforge.net/): Librería en php para la generación de códigos QR.
