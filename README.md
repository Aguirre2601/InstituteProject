# CRUD-DB2-project
-------------------------------------------------------------------------------
CRUD Instituto

Algunas modificaciones al CRUD:

Logo web
    • Cambiar mensaje del logo por “Registro alumnos Instituto 93”.
      
Nuevo alumno
    • Agregar registro mail.
    • Agregar registro carrera en un textbox o combobox.
    • Agregar registro calle.
    • Agregar registro localidad.

Editar alumno
    • Agregar botón volver a la izquierda.
    • Ubicar botón actualizar a la derecha.

Eliminar
    • Cambiar mensaje de la ventana de alerta por “¿Quiere borrar registro de alumno?.

Tabla alumnos
    • Mostrar título “Listado de alumnos”.

Diseño
    • Agregar logo del Instituto 93.
    • Cambiar los colores a los colores institucionales.
    • Agregar foto representativa de cada sección.
    • Agregar iconos a los botones o reemplazarlos por iconos.

--------------------
para sumar puntos 
login 
Agregar el envio de email
ponerle onda al diseño


## Para hacer su Dominio virtual
c:\windows\system32\drivers\etc\host
Abro el archivo host con bloc de notas o con visual Code con permisos de Admin y escribo:
127.0.0.1 localhost
127.0.0.1 Instituto93.com
Guardo.
Luego en la carpeta de Apache uno el nuevo server a una carpeta que tenga el ejecutable del programa.
Configurar la virtualHost
c:\wamp64\bin\apache\apachex.x.x\conf\extra\httpd-vhost.conf 
Ahi pegar lo siguiente:
# Virtual Hosts
#
<VirtualHost _default_:80>
  ServerName localhost
  ServerAlias localhost
  DocumentRoot "${INSTALL_DIR}/www"
  <Directory "${INSTALL_DIR}/www/">
    Options +Indexes +Includes +FollowSymLinks +MultiViews
    AllowOverride All
    Require local
  </Directory>
</VirtualHost>

<VirtualHost *:80>
  ServerName Instituto93.com
  ServerAlias Instituto93.com
  DocumentRoot "C:\CRUD-INSTITUTO\public"
  <Directory "C:\CRUD-INSTITUTO\public/">
    Options +Indexes +Includes +FollowSymLinks +MultiViews
    AllowOverride All
    Require local
  </Directory>
</VirtualHost>

Guardo.Listo.
## Si en wamppserve no funciona su nuevo hsot Virtual
Debe vaciar correctamente la caché de resolución de DNS. Con el siguiente comando por consola, ejecutando como Administrador: 

ipconfig /flushdns


## Contraseñas
alumno123: alumno123
profesor123: profe123
director123: director123

## PARA EL ENVIO DE MAIL 
composer require phpmailer/phpmailer

## Name
CRUD INSTITUTO

## Usar una Constante de URL
Para evitar problemas si algún día cambias de dominio o mueves el proyecto a una subcarpeta (ej: localhost/mi-proyecto), es una muy buena práctica definir una constante para la URL base en tu index.php.

1. En public/index.php (Agregar al inicio):

// Define la URL base de tu proyecto (sin barra al final)
// Cambia esto si mueves el proyecto de servidor
define('BASE_URL', 'http://instituto93.com');
2. En app/views/layouts/header.php (Usar la constante):

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
De esta forma, te aseguras de que los estilos carguen siempre, sin importar la profundidad de la ruta en la que te encuentres (/profesor/dashboard, etc.).