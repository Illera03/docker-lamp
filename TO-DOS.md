## El Sistema Web puede versar sobre cualquier temática y debe incluir las siguientes funcionalidades:

• ## (HECHO) ## Registro de usuarios comprobando que los datos introducidos cumplan una serie de condiciones (El
formulario debe incluir ejemplos claros):

    ◦ Nombre y apellidos sólo aceptarán texto.

    ◦ DNI sólo aceptará formatos 11111111-Z y comprobará que la letra se corresponde realmente al número del DNI (existe un algoritmo para saber qué letra se corresponde con cada número).

    ◦ Teléfono sólo aceptará números de 9 dígitos.

    ◦ Fecha de Nacimiento sólo admitirá fechas en formato aaaa-mm-dd (1999-08-26).

    ◦ Email sólo aceptará formatos de email válidos (ejemplo@servidor.extensión).

• ## (HECHO) ##Identificación de usuarios en base a un nombre de usuario y una contraseña.

• ## (HECHO) ## Modificación de datos del usuario identificado (hay que volver a realizar las comprobaciones
correspondientes a los formatos).

• Posibilidad de añadir elementos al sistema. Cada elemento debe tener 5 campos de datos.

• ## (HECHO) ## Generación de un listado de los elementos que se encuentren en la Base de Datos. En el listado se
deben mostrar dos de los 5 campos que tiene cada elemento.

• Posibilidad de modificar datos de los elementos. A partir del listado, debe poderse seleccionar uno
de los elementos, ver sus 5 campos con los valores correspondientes y modificar el valor de
cualquiera de los campos.

• Posibilidad de eliminar elementos del sistema. A partir del listado, debe poderse seleccionar uno de
los elementos y eliminarlo del sistema. El sistema debe pedir confirmación antes de proceder a
borrar el elemento.

## ESTRUCTURA

El proyecto deber seguir una estructura concreta, formada por URLs, IDs de formulario e IDs de botones.

• Home: localhost:81/

• Registro: localhost:81/register

    ◦ id formulario: register_form

    ◦ id boton: register_submit

• localhost:81/login

    ◦ id formulario: login_form

    ◦ id boton: login_submit

• localhost:81/show_user?user={x}

• localhost:81/modify_user?user={x}

    ◦ id formulario: user_modify_form

    ◦ id boton: user_modify_submit

• localhost:81/items

• localhost:81/add_item

    ◦ id formulario: item_add_form

    ◦ id boton: item_add_submit

• localhost:81/show_item?item={x}

• localhost:81/modify_item?item={x}

    ◦ id formulario: item_modify_form

    ◦ id boton: item_modify_submit

• localhost:81/delete_item?item={x}

    ◦ id boton: item_delete_submit

## REQUISITOS DE ENTREGA

El sistema debe estar disponible en un repositorio GitHub público y debe contener:

◦ Un archivo en texto plano (README.md2), que respete la sintaxis Markdown3 que incluya:

    ▪ Los nombres de las personas que forman el grupo.

    ▪ Instrucciones detalladas de como desplegar el proyecto mediante Docker.

◦ Un archivo de texto (.pdf) con instrucciones detalladas de cómo usar el sistema, incluyendo
capturas de pantalla.

◦ Los archivos Docker necesarios (Dockerfile, docker-compose)

◦ Los archivos HTML, PHP, JavaScript y CSS correspondientes.

◦ El script (.sql) que permita restaurar la base de datos MariaDB.

◦ Los archivos creados deben estar comentados indicando los principales pasos que se realizan.

◦items

    ◦add item

    ◦mostrar tabla seleccionable (2 columnas)

        ◦ show item (muestra el item seleccionado)
            ◦ modify (boton)
            ◦ delete (boton)

Conseguir hacerlo sin sesion, y solo con url
Para el modify, intentar meter todo el formulario en php
