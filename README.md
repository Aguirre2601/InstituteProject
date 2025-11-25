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

## Contraseñas
alumno: alumno123
profesor: profe123
director: director123

## Name
CRUD INSTITUTO

## Description
Let people know what your project can do specifically. Provide context and add a link to any reference visitors might be unfamiliar with. A list of Features or a Background subsection can also be added here. If there are alternatives to your project, this is a good place to list differentiating factors.

## Badges
On some READMEs, you may see small images that convey metadata, such as whether or not all the tests are passing for the project. You can use Shields to add some to your README. Many services also have instructions for adding a badge.

## Visuals
Depending on what you are making, it can be a good idea to include screenshots or even a video (you'll frequently see GIFs rather than actual videos). Tools like ttygif can help, but check out Asciinema for a more sophisticated method.

## Installation
Within a particular ecosystem, there may be a common way of installing things, such as using Yarn, NuGet, or Homebrew. However, consider the possibility that whoever is reading your README is a novice and would like more guidance. Listing specific steps helps remove ambiguity and gets people to using your project as quickly as possible. If it only runs in a specific context like a particular programming language version or operating system or has dependencies that have to be installed manually, also add a Requirements subsection.

## Usage
Use examples liberally, and show the expected output if you can. It's helpful to have inline the smallest example of usage that you can demonstrate, while providing links to more sophisticated examples if they are too long to reasonably include in the README.

## Support
Tell people where they can go to for help. It can be any combination of an issue tracker, a chat room, an email address, etc.

## Roadmap
If you have ideas for releases in the future, it is a good idea to list them in the README.

## Contributing
State if you are open to contributions and what your requirements are for accepting them.

For people who want to make changes to your project, it's helpful to have some documentation on how to get started. Perhaps there is a script that they should run or some environment variables that they need to set. Make these steps explicit. These instructions could also be useful to your future self.

You can also document commands to lint the code or run tests. These steps help to ensure high code quality and reduce the likelihood that the changes inadvertently break something. Having instructions for running tests is especially helpful if it requires external setup, such as starting a Selenium server for testing in a browser.

## Authors and acknowledgment
Show your appreciation to those who have contributed to the project.

## License
For open source projects, say how it is licensed.

## Project status
If you have run out of energy or time for your project, put a note at the top of the README saying that development has slowed down or stopped completely. Someone may choose to fork your project or volunteer to step in as a maintainer or owner, allowing your project to keep going. You can also make an explicit request for maintainers.
