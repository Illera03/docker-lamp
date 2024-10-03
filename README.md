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

## INTEGRANTES DEL GRUPO:

Jorge Illera Rivera

Iker Argulo Galán

Bruno Izaguirre

Oscar Lijerón

Ander Javier Corral

Maria Bogajo
