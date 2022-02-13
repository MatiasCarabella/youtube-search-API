<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Youtube Search API

###  Documento Técnico - Aivo Challenge

#### Requerimiento:
- Desarrollar un endpoint que devuelva hasta 10 resultados de una búsqueda en YouTube, dada una 
palabra clave.

##### Parámetros obligatorios:
- **_published_at_** **(*)**
- **_id_**
- **_title_**
- **_description_**
- **_thumbnail_**

**(*)** - Se observa que se usa la notación ‘snakecase’ _(separando las palabras con guiones bajos)_, en base a 
esto todos los demás parámetros respetarán esta convención

##### Parámetros opcionales:
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

#### Condiciones generales:
- Proyecto desarrollado en **PHP7**:
    - Se desarrolló utilizando la versión más nueva de PHP7 al día de la fecha: **PHP 7.4.16**
- Framework opcional y a elección:
    - El proyecto se desarrolló con **Laravel 8** y **Composer**
- El proyecto debe estar disponible en GitHub o BitBucket:
    - Se eligió **GitHub**, en el siguiente repositorio: <a href="https://github.com/MatiasCarabella/youtubeSearchAPI">_MatiasCarabella/youtubeSearchAPI (github.com)_</a>
- El proyecto debe ser testeable localmente, con la documentación necesaria de cómo hacerlo:
Correcto, a la brevedad estaré abordando el sencillo proceso de cómo hacer correr la aplicación
- Tests - Opcionales pero valorados:
Los hay, se complementó el proyecto con un par de tests, que también detallaré más adelante.
- Todo valor agregado es bienvenido:
La aplicación tiene acepta una serie de argumentos opcionales que complementan la ‘keyword’ 
central, que también se detallarán eventualmente, a priori adelanto que son:
• api_key
(indica la clave de autenticación obligatoria para usar la API de Google/Youtube, si 
este parámetro está ausente – considerando que es un proyecto demo – se usa mi 
key personal)
• results_per_page
(modifica la cantidad de resultados por página, hasta 10 como indica el 
requerimiento, si este parámetro está ausente el default es 10)
• page_token
(permite navegar entre las distintas páginas de resultados, dando uso a los 
parámetros next_page_token y prev_page_token, si este parámetro está ausente 
simplemente se posiciona en la página #1)
▪ Instalación:
Para ejecutar correctamente el proyecto se requieren:
- PHP 7.4.16, u otra versión de PHP7 en su defecto.
- Composer, como administrador de dependencias
- Git, opcional pero sugerido para agilizar el proceso
Teniendo eso, los pasos a seguir son los siguientes:
1. Ingresar al repositorio del proyecto en GitHub y copiar la URL del mismo:
https://github.com/MatiasCarabella/youtubeEndpoint.git
2. Crear la carpeta en donde se desee descargar el proyecto, acceder a ella desde la consola de 
preferencia (como puede ser el cmd en Windows) y ejecutar los siguientes comandos:
git init
git pull https://github.com/MatiasCarabella/youtubeEndpoint.git
3. Ya tenemos el proyecto descargado, ahora -aún desde la consola- ejecutamos el siguiente comando 
para descargar las dependencias correspondientes:
composer install
4. Por último, se prepara el archivo .env y se genera la clave de encriptación necesaria con los 
siguientes comandos:
copy .env.example .env (Windows) ó cp .env.example .env (Linux)
php artisan key:generate
Listo todo, de ahora en adelante la forma de ejecutar el proyecto desde su carpeta es con el comando
php artisan serve
Como validación, si accedemos a esa URL ya deberíamos poder ver la ‘Laravel homepage’:
▪ Utilizando el endpoint: 
Finalmente, yendo a lo más entretenido, ya estamos en condiciones de utilizar el desarrollo.
La URL del endpoint se compone de la siguiente manera:
http://127.0.0.1:8000/api/youtubeSearch/{texto_a_buscar}
Un ejemplo:
http://127.0.0.1:8000/api/youtubeSearch/paradise
Devolverá resultados relacionados a la palabra ‘paradise’. Palabra o frase, es indistinto:
http://127.0.0.1:8000/api/youtubeSearch/britney%20spears
Respuesta ejemplo:
Muy lindo, pero como mencionamos arriba también están a disposición los parámetros opcionales 
results_per_page, page_token y api_key.
Un ejemplo puede ser http://127.0.0.1:8000/api/youtubeSearch/eminem?results_per_page=1:
Y para empezar a recorrer las páginas de resultados:
http://127.0.0.1:8000/api/youtubeSearch/eminem?results_per_page=1&page_token=CAEQAA
Llegado este punto, y a medida que sumamos parámetros, puede resultar más cómodo consumir la API por medio 
de un cliente como Postman.
El último parámetro opcional es api_key, que se dan de alta desde la Google Developer Platform, este proyecto por 
defecto funciona con la mía personal, pero se puede especificar una a gusto mediante el parámetro.
Observaciones:
▪ Si se ingresa un valor 
inválido en el parámetro 
results_per_page, defaultea 
a 10
▪ Si se ingresa un valor 
inválido en el parámetro 
page_token o api_key, se 
mostrará el mensaje error 
tal cual devuelve la API de 
YouTube. 
▪ Estructura del proyecto:
El grueso del desarrollo se encuentra en los siguientes archivos del proyecto:
routes->api.php
app->Http->Controllers->YoutubeController.php
tests->Feature->YoutubeControllerTest.php
Todo está comentado como corresponde a efectos de facilitar la comprensión del código:
▪ Tests:
Como mencioné al principio, se agregaron un par de tests para validar el funcionamiento del servicio. 
Estos son:
1. Validar que una consulta ejemplo devuelva status HTTP 200 (OK)
2. Validar que el formato del JSON obtenido se corresponda con el esperado
Para ejecutarlos, simplemente se corre el siguiente comando desde la carpeta del proyecto:
php artisan test tests\Feature\YoutubeControllerTest.php
▪ Cierre:
Me alegra poder decir que la condición de ‘Have fun!!’ de los requerimientos también la cumplí, 
realmente entretenido el proyecto! 
Muchas gracias para quien haya leído hasta aquí, saludos!
