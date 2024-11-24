# Docker LAMP

Linux + Apache + MariaDB (MySQL) + PHP 7.2 on Docker Compose. Mod_rewrite enabled by default.

## COMO DESPLEGAR EL PROYECTO MEDIANTE DOCKER:

Para instalar Docker:

```bash
$ apt install docker.io
```

Docker necesita privilegios de root. Para evitar el uso de sudo:

Crear grupo docker:

```bash
$ sudo groupadd docker
```

Añadir usuario actual al grupo docker:

```bash
$ sudo usermod -aG docker $USER
```

Construye la imagen web:

```bash
$ docker build -t="web" .
```

Instalar docker compose:

```bash
$ sudo apt install docker-compose
```

Despliega los servicios mediante

```bash
$ docker-compose up
```

Para parar los servicios, en otra terminal,

```bash
$ docker-compose down
```

ACCIONES NECESARIAS PARA EL FUNCIONAMIENTO DE LOS LOGS:

Crear la carpeta logs dentro de docker-lamp (si no está ya creada):

```bash
$ mkdir -p /home/$USER/docker-lamp/logs
```

Crear el archivo login_attempts.log dentro de la carpeta logs:

```bash
$ touch /home/$USER/docker-lamp/logs/login_attempts.log
```

Asignar permisos y propietario a la carpeta logs:

```bash
$ sudo chown -R www-data:www-data /home/$USER/docker-lamp/logs
$ sudo chmod -R 755 /home/$USER/docker-lamp/logs
```

## INTEGRANTES DEL GRUPO:

Jorge Illera Rivera

Iker Argulo Galán

Bruno Izaguirre

Oscar Lijerón

Ander Javier Corral

Maria Bogajo
