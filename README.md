# Sistema de Gestión del Instituto Educativo

## Descripción del Proyecto

Este proyecto es un sistema completo de gestión para un instituto educativo, desarrollado como una aplicación web CRUD (Create, Read, Update, Delete). Permite la administración de usuarios con diferentes roles: alumnos, profesores y directores. El sistema incluye funcionalidades de autenticación segura, gestión de perfiles, creación de cuentas de usuario, envío de correos electrónicos y dashboards personalizados según el rol del usuario.

### Características Principales
- **Autenticación y Autorización**: Sistema de login con verificación de contraseñas hasheadas y control de acceso basado en roles.
- **Gestión de Usuarios**: Creación, edición y eliminación de perfiles para alumnos y profesores.
- **Dashboards Personalizados**: Interfaces específicas para cada rol con funcionalidades adaptadas.
- **Recuperación de Contraseña**: Sistema de recuperación de contraseñas vía email.
- **Middleware de Seguridad**: Control de acceso a rutas protegidas mediante middleware de verificación de roles.

## Tecnologías Utilizadas

- **Backend**: PHP 7+ con arquitectura MVC (Model-View-Controller)
- **Base de Datos**: MySQL
- **Gestión de Dependencias**: Composer
- **Envío de Correos**: PHPMailer
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5.3.3 para diseño responsivo
- **Autoload**: PSR-4 para carga automática de clases

## Modelo Responsivo

El proyecto destaca por su diseño completamente responsivo, implementado mediante Bootstrap 5. El sistema se adapta perfectamente a diferentes dispositivos y tamaños de pantalla, desde móviles hasta desktops. Utiliza el sistema de grid de Bootstrap, componentes responsivos y clases de utilidad para garantizar una experiencia de usuario óptima en cualquier dispositivo.

## Arquitectura de Software Moderna

La aplicación sigue una arquitectura MVC moderna con las siguientes características:

### Estructura del Proyecto
```
- app/
  - Controllers/     # Controladores para manejar la lógica de negocio
  - Models/          # Modelos para interactuar con la base de datos
  - Views/           # Vistas para la presentación de datos
  - Services/        # Servicios auxiliares (ej: envío de correos)
  - Middleware/      # Middleware para control de acceso y seguridad

- core/
  - Router.php       # Enrutador personalizado con soporte para middleware
  - DataBase.php     # Clase para conexión a base de datos

- config/            # Archivos de configuración
- public/            # Punto de entrada público y assets estáticos
```

### Características Arquitectónicas
- **Separación de Responsabilidades**: MVC clara con controladores delgados y modelos enfocados en datos.
- **Middleware**: Sistema de middleware para autenticación y autorización.
- **Autoload PSR-4**: Carga automática de clases siguiendo estándares modernos.
- **Enrutamiento Personalizado**: Router con soporte para parámetros dinámicos y middleware.
- **Servicios**: Capa de servicios para funcionalidades transversales como envío de emails.
- **Seguridad**: Uso de prepared statements, hash de contraseñas y validación de entrada.

## Instalación y Configuración

### Requisitos Previos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Composer
- Servidor web (Apache/Nginx) con soporte para URL rewriting

### Pasos de Instalación
1. Clonar el repositorio
2. Instalar dependencias: `composer install`
3. Configurar la base de datos en `config/db.php`
4. Importar el esquema: `mysql -u usuario -p < instituto_db.sql`
5. Configurar el dominio virtual según las instrucciones abajo

### Configuración de Dominio Virtual
Para desarrollo local, configurar un dominio virtual apuntando a la carpeta `public/`.

**Archivo hosts** (`c:\windows\system32\drivers\etc\hosts`):
```
127.0.0.1 Instituto93.com
```

**Configuración Apache** (`httpd-vhosts.conf`):
```apache
<VirtualHost *:80>
  ServerName Instituto93.com
  DocumentRoot "C:\AguirreProyectos\2026\crud-instituto\public"
  <Directory "C:\AguirreProyectos\2026\crud-instituto\public/">
    Options +Indexes +Includes +FollowSymLinks +MultiViews
    AllowOverride All
    Require local
  </Directory>
</VirtualHost>
```

### Credenciales de Prueba
- **Alumno**: usuario: alumno123, contraseña: alumno123
- **Profesor**: usuario: profesor123, contraseña: profe123
- **Director**: usuario: director123, contraseña: director123

### Envío de Correos
Para funcionalidad completa de envío de emails, instalar PHPMailer:
```bash
composer require phpmailer/phpmailer
```

## Uso del Sistema

1. Acceder a `http://Instituto93.com`
2. Iniciar sesión con las credenciales apropiadas
3. Navegar por el dashboard correspondiente al rol
4. Gestionar perfiles, crear usuarios (según permisos) y utilizar las funcionalidades disponibles

## Contribución

Para contribuir al proyecto:
1. Fork el repositorio
2. Crear una rama para la nueva funcionalidad
3. Realizar los cambios siguiendo la arquitectura MVC
4. Enviar un pull request

## Licencia

Este proyecto está bajo la licencia MIT.

## ACTUALIZAR EL AUTOLOAD, en consola
composer dump-autoload

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
