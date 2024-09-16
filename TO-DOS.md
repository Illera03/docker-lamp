## El Sistema Web puede versar sobre cualquier temática y debe incluir las siguientes funcionalidades:

• ## (HECHO) ## Registro de usuarios comprobando que los datos introducidos cumplan una serie de condiciones (El
formulario debe incluir ejemplos claros):

    ◦ Nombre y apellidos sólo aceptarán texto.

    ◦ DNI sólo aceptará formatos 11111111-Z y comprobará que la letra se corresponde realmente al número del DNI (existe un algoritmo para saber qué letra se corresponde con cada número).

    ◦ Teléfono sólo aceptará números de 9 dígitos.

    ◦ Fecha de Nacimiento sólo admitirá fechas en formato aaaa-mm-dd (1999-08-26).

    ◦ Email sólo aceptará formatos de email válidos (ejemplo@servidor.extensión).

• Identificación de usuarios en base a un nombre de usuario y una contraseña.

• Modificación de datos del usuario identificado (hay que volver a realizar las comprobaciones
correspondientes a los formatos).

• Posibilidad de añadir elementos al sistema. Cada elemento debe tener 5 campos de datos.

• Generación de un listado de los elementos que se encuentren en la Base de Datos. En el listado se
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

• localhost:81/user/{x}/modify
◦ id formulario: user_modify_form

    ◦ id boton: user_modify_submit

• localhost:81/items

• localhost:81/items/add

    ◦ id formulario: item_add_form

    ◦ id boton: item_add_submit

• localhost:81/item/{x}

• localhost:81/item/{x}/modify

    ◦ id formulario: item_modify_form

    ◦ id boton: item_modify_submit

• localhost:81/item/{x}/delete

    ◦ id boton: item_delete_submit
