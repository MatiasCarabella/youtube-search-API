<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Youtube Search API - Aivo Challenge - Documento Técnico

### Requerimiento
- Desarrollar un endpoint que devuelva hasta 10 resultados de una búsqueda en YouTube, dada una 
palabra clave.

#### Parámetros obligatorios
- **_published_at_** **(*)**
- **_id_**
- **_title_**
- **_description_**
- **_thumbnail_**

**(*)** - Se observa que se usa la notación ‘snakecase’ _(separando las palabras con guiones bajos)_, en base a 
esto todos los demás parámetros respetarán esta convención.

#### Parámetros opcionales
- **_extra_** _(datos adicionales que se quieran agregar a criterio libre)_

En cuanto a esto decidí agregar los parámetros **_direct_link_** y **_channel_title_**

En conclusión, los JSONs de los videos obtenidos tendrán el siguiente formato:

<p align="center"><img src="https://i.imgur.com/Xj3h916.png"></p>

A su vez, la lista de videos obtenidos estará contenida en un parámetro videos, el cual compartirá el ‘nivel 
superior’ del JSON respuesta con los siguientes parámetros generales:
- **_total_results_**
- **_results_per_page_**
- **_next_page_token_** **(*)** _(permite navegar las páginas de resultados obtenidos)_
- **_prev_page_token_** **(*)** _(idem ‘next_page_token’)_

Finalmente, el formato del JSON respuesta será el siguiente:

<p align="center"><img src="https://i.imgur.com/4qLl599.png"></p>

**(*)** - El parámetro **_next_page_token_** sólo aparecerá si efectivamente hay más páginas de resultados, 
asimismo, **_prev_page_token_** se mostrará únicamente si no nos encontramos posicionados en la página 1.

### Condiciones generales
- _Proyecto desarrollado en **PHP7**:_
    - Se desarrolló utilizando la versión más nueva de PHP7 al día de la fecha: **PHP 7.4.16**
- _Framework opcional y a elección:_
    - El proyecto se desarrolló con **Laravel 8** y **Composer**
- _El proyecto debe estar disponible en GitHub o BitBucket:_
    - Se eligió **GitHub**, en el siguiente repositorio: <a href="https://github.com/MatiasCarabella/youtubeSearchAPI">_MatiasCarabella/youtubeSearchAPI (github.com)_</a>
- _El proyecto debe ser testeable localmente, con la documentación necesaria de cómo hacerlo:_
    - Correcto, a la brevedad estaré abordando el sencillo proceso de cómo hacer correr la aplicación
- _Tests - Opcionales pero valorados:_
    - Los hay, se complementó el proyecto con un par de tests, que también detallaré más adelante.
- _Todo valor agregado es bienvenido:_
    - La aplicación tiene acepta una serie de argumentos opcionales que complementan la ‘keyword’ central, que también se detallarán eventualmente, a priori adelanto que son:
        -  **api_key** _(indica la clave de autenticación obligatoria para usar la API de Google/Youtube, si este parámetro está ausente – considerando que es un proyecto demo – se usa mi key personal)_
        - **results_per_page** _(modifica la cantidad de resultados por página, hasta 10 como indica el requerimiento, si este parámetro está ausente el default es 10)_
        - **page_token** _(permite navegar entre las distintas páginas de resultados, dando uso a los parámetros next_page_token y prev_page_token, si este parámetro está ausente simplemente se posiciona en la página #1)_

### Instalación
Para ejecutar correctamente el proyecto se requieren:
- **PHP 7.4.16**, u otra versión de PHP7 en su defecto.
- <a href="https://getcomposer.org/">**Composer**</a>, como administrador de dependencias.
- <a href="https://git-scm.com/downloads">**Git**</a>, opcional pero sugerido para agilizar el proceso.

Teniendo eso, los pasos a seguir son los siguientes:

1. Ingresar al <a href="https://github.com/MatiasCarabella/youtubeSearchAPI">repositorio del proyecto en GitHub</a> y copiar la URL del mismo:

_**<p align="center">https://github.com/MatiasCarabella/youtubeSearchAPI.git</p>**_

2. Crear la carpeta en donde se desee descargar el proyecto, acceder a ella desde la terminal/consola de 
preferencia y ejecutar los siguientes comandos:

```
git init
git pull https://github.com/MatiasCarabella/youtubeEndpoint.git
```

3. Ya tenemos el proyecto descargado, ahora -aún desde la consola- ejecutamos el siguiente comando 
para descargar las dependencias correspondientes:

```
composer install
```

4. Por último, se prepara el archivo .env y se genera la clave de encriptación necesaria con los 
siguientes comandos:

`copy .env.example .env` _(Windows)_ ó `cp .env.example .env` _(Linux)_
```
php artisan key:generate
```

Listo todo, de ahora en adelante la forma de ejecutar el proyecto desde su carpeta es con el comando
```
php artisan serve
```
<p align="center"><img src="https://i.imgur.com/b4TImjs.png"></p>

Como validación, si accedemos a esa URL ya deberíamos poder ver la _‘Laravel homepage’_:

<p align="center"><img src="https://i.imgur.com/CcdzVK5.png"></p>

### Utilización de la API

Finalmente, yendo a lo más entretenido, ya estamos en condiciones de utilizar el desarrollo.
La URL del endpoint se compone de la siguiente manera:

_**<p align="center">http://127.0.0.1:8000/api/youtubeSearch/ + {texto_a_buscar}</p>**_

Un ejemplo:

_**<p align="center">http://127.0.0.1:8000/api/youtubeSearch/paradise</p>**_

Devolverá resultados relacionados a la palabra ‘paradise’. Palabra o frase, es indistinto:

_**<p align="center">
http://127.0.0.1:8000/api/youtubeSearch/britney%20spears</p>**_

Respuesta ejemplo:

<p align="center"><img src="https://i.imgur.com/bpM1cr8.png"></p>

Muy lindo, pero como mencionamos arriba también están a disposición los parámetros opcionales 
**results_per_page**, **page_token** y **api_key**.

Un ejemplo puede ser http://127.0.0.1:8000/api/youtubeSearch/eminem?results_per_page=1:

<p align="center"><img src="https://i.imgur.com/9nbXDEY.png"></p>

Y para empezar a recorrer las páginas de resultados:

http://127.0.0.1:8000/api/youtubeSearch/eminem?results_per_page=1&page_token=CAEQAA

Llegado este punto, y a medida que sumamos parámetros, puede resultar más cómodo consumir la API por medio 
de un cliente como <a href="https://www.postman.com/">Postman</a>.

<p align="center"><img src="https://i.imgur.com/NMbsKRv.png"></p>

El último parámetro opcional es un **api_key**, los cuales se dan de alta desde la <a href="https://console.developers.google.com/apis/credentials">Google Cloud Platform</a>, este proyecto por defecto funciona con la mía personal, pero se puede especificar una a gusto mediante el parámetro.

### Observaciones

- Si se ingresa un valor inválido en el parámetro **_results_per_page_**, defaultea a **10**.
- Si se ingresa un valor inválido en el parámetro **_page_token_** o **_api_key_**, se mostrará el mensaje error tal cual devuelve la API de YouTube.

<p align="center"><img src="https://i.imgur.com/rokIz2c.png"></p>

### Estructura del proyecto

El grueso del desarrollo se encuentra en los siguientes archivos del proyecto:

`routes->api.php`

`app->Http->Controllers->YoutubeController.php`

`tests->Feature->YoutubeControllerTest.php`

A efectos de facilitar la comprensión del código, todo está comentado como corresponde:

<p align="center"><img src="https://i.imgur.com/6ApNbjJ.png"></p>

### Tests

Como mencioné al principio, se agregaron un par de tests para validar el funcionamiento del servicio. Estos son:

1. Validar que una consulta ejemplo devuelva status HTTP 200 - OK.
2. Validar que el formato del JSON resultante se corresponda con el estipulado.

Para ejecutarlos, simplemente se corre el siguiente comando desde la carpeta del proyecto:
```
php artisan test tests\Feature\YoutubeControllerTest.php
```

### Cierre
Me alegra poder decir que el _‘Have fun!!’_ de la consigna del Challenge también fue cumplida, ¡Realmente entretenido el proyecto!

Para quien haya leído hasta aquí, ¡Muchas gracias y saludos!

_<p align="right">Matías Carabella - Back-end Developer</p>_
