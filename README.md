<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://i.imgur.com/ckGOMl1.png" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Youtube Search API - Aivo Challenge - Technical Document

## Index
  * [General requirement](#general-requirement)
    + [Mandatory parameters](#mandatory-parameters)
    + [Optional parameters](#optional-parameters)
  * [Project guidelines](#project-guidelines)
  * [Installation](#installation)
    + [PHP | Composer | Artisan](#php--composer--artisan)
    + [Docker compose](#docker-compose)
  * [Utilización de la API](#utilización-de-la-api)
  * [Observaciones](#observaciones)
  * [Estructura del proyecto](#estructura-del-proyecto)
  * [Tests](#tests)
  * [Cierre](#cierre)


## General requirement
- Develop an endpoint that returns up to 10 results from a YouTube search, given a keyword

### Mandatory parameters
- **_published_at_** **(*)**
- **_id_**
- **_title_**
- **_description_**
- **_thumbnail_**

**(*)** - It is noted that the 'snakecase' notation is used, so all other parameters will adhere to this convention.

### Optional parameters
- **_extra_** _(additional data that you want to add at your discretion)_

In regards to this, I decided to add the parameters  **_direct_link_** and **_channel_title_**, so each **video** will have the following format:
```json
{
   "published_at":"2011-10-19T02:42:54Z",
   "id":"1G4isv_Fylg",
   "title":"Coldplay - Paradise (Official Video)",
   "description":"Coldplay - Paradise is taken from the album Mylo Xyloto released in 2011 [...]",
   "thumbnail":"https://i.ytimg.com/vi/1G4isv_Fylg/default.jpg",
   "extra":{
      "direct_link":"https://www.youtube.com/watch?v=1G4isv_Fylg",
      "channel_title":"Coldplay"
   }
}
```

The list of fetched videos will be contained in a **_videos_** parameter, which will share the 'top level' of the response JSON with the following general parameters:
- **_total_results_**
- **_results_per_page_**
- **_next_page_token_** **(*)** _(makes it possible to navigate between the result pages)_
- **_prev_page_token_** **(*)** _(same as ‘next_page_token’)_

Finally, the general format of the response will be as follows:

```json
{
   "next_page_token":"CAEQAA",
   "total_results":1000000,
   "results_per_page":1,
   "videos":[
      
   ]
}
```

**(*)** - The **_next_page_token_** parameter will only appear if there are more pages of results, likewise, **_prev_page_token_** will only appear if we are not positioned on page 1.

## Project guidelines
- _Must be developed in **PHP7**:_
    - _(13/03/2021):_ Developed using PHP7's newest version as of today: **PHP 7.4.16**
    - _(Update 26/02/2023):_ Upgraded to the most recent version: **PHP 8.2.3**
- _**Framework** of your choice (Optional):_
    - _(13/03/2021):_ Developed with **Laravel 8**
    - _(Update 12/07/2022):_ Upgraded to **Laravel 9**
- _The project must be available on **GitHub** or **BitBucket**:_
    - **GitHub** was chosen, in the present repository: <a href="https://github.com/MatiasCarabella/youtubeSearchAPI">_MatiasCarabella/youtubeSearchAPI (github.com)_</a>
- _The project must be **testable** locally, with the necessary **documentation** on how to do it:_
    - Correct, I will be addressing the simple process of how to get the application running in the next section.
- _**Tests** (Optional):_
    - The project was made more robust with the addition of tests, which I will also detail later.
- _Any added value is welcome:_
    - The application accepts a series of optional arguments. These are:
        -  **api_key** _(holds the mandatory authentication key to use the Google/Youtube API, if this parameter is absent it uses the **API_KEY_DEFAULT** from the .env file)_
        - **results_per_page** _(modifies the number of results per page, up to 10 as indicated in the project requirement, if this parameter is absent the default is also 10)_
        - **page_token** _(allows to navigate between the different pages of results, making use of the parameters **_next_page_token_** and **_prev_page_token_**, if this parameter is absent it simply defaults to page #1)_

## Installation

Generate the folder where you'll download the project, access it from the terminal/console of preference and run the following commands:

```
git init
git pull https://github.com/MatiasCarabella/youtubeSearchAPI.git
```

Now, in order to get the application up and running, you have two options:
### PHP | Composer | Artisan
Requirements:
- <a href="https://www.php.net/">**PHP8**</a> installed.
- <a href="https://getcomposer.org/">**Composer**</a> installed (dependency manager).

1. Run the following command to download the necessary dependencies:

```
composer install
```

2. Copy the .env file _(Optional: Set a custom **API_KEY_DEFAULT** value)_

`copy .env.example .env` _(Windows)_ 

`cp .env.example .env` _(Linux)_

3. Generate the encryption key
```
php artisan key:generate
```

**All done!** From now on you can run the project from its root folder with the following command:
```
php artisan serve
```
<p align="center"><img src="https://i.imgur.com/F9U9cQR.png"></p>

If we access that URL _(http://localhost:8000)_ we should be able to see the 'Laravel homepage':

<p align="center"><img src="https://i.imgur.com/F1Pc6jF.png"></p>

### Docker compose
Requirements:
- <a href="https://www.docker.com/">**Docker**</a> installed.

1. Run the following command to get the project up:
```
docker compose up
```

**All done!**


### Utilización de la API

Finalmente, yendo a lo más entretenido, ya estamos en condiciones de utilizar el desarrollo.
La URL del endpoint es la siguiente:

_**<p align="center">http://localhost:8000/api/youtube-search</p>**_

Si ingresamos a dicha URL, veremos algo como esto:

<p align="center"><img src="https://i.imgur.com/pbnIuWg.png"></p>

Eso es esperable _(puesto que no le hemos indicado el texto a buscar)_, pero nos da confirmación de que la API se está ejecutando exitosamente!<br>
Ahora, para probar efectivamente el servicio podemos utilizar un cliente como <a href="https://www.postman.com/">**Postman**</a>:

<p align="center"><img src="https://i.imgur.com/LcEnhgM.png"></p>
Como bien se puede apreciar allí, es simplemente cuestión de enviar un JSON con el texto a buscar en el campo 'search':

```json
{
    "search": "Paradise"
}
```

El último requerimiento obligatorio es una **api_key**, las cuales se dan de alta desde la <a href="https://console.developers.google.com/apis/credentials">**Google Cloud Platform**</a>.<br>
Esta puede configurarse de dos maneras:<br>
- Como **Header** del Request _('api_key': 'XXXXXXXXXXXXX')_<br>
- En la variable 'API_KEY_DEFAULT' archivo **ENV** del proyecto _(En caso de no enviarse como Header, se lee de aquí)_<br>
```
API_KEY_DEFAULT=XXXXXXXXXXXXX
```

En ambos casos, si se coloca un **api_key** inválido se mostrará el error tal cual lo retorna la API de Google:
<p align="center"><img src="https://i.imgur.com/1HWHXzm.png"></p>

Finalmente, y como se mencionó al principio, también están a disposición los campos opcionales **results_per_page**, **page_token**.

<p align="center"><img src="https://i.imgur.com/j5ZgZKa.png"></p>

## Observaciones

- Si se ingresa un valor inválido en el parámetro **_results_per_page_**, defaultea a **10**.
- Si se ingresa un valor inválido en el parámetro **_page_token_** o **_api_key_**, se mostrará el mensaje error tal cual devuelve la API de Google.

## Estructura del proyecto

El grueso del desarrollo se encuentra en los siguientes archivos del proyecto:

`routes->api.php`

`app->Http->Controllers->YoutubeController.php`

`app->Services->YoutubeServices.php`

`tests->Feature->YoutubeTest.php`

A efectos de facilitar la comprensión del código, todo está comentado como corresponde:

<p align="center"><img src="https://i.imgur.com/X4R7C6M.png"></p>

## Tests

Como se mencionó al inicio, hay tests para validar el funcionamiento del servicio. Estos son:

1. Validar que una consulta ejemplo devuelva status HTTP 200 - OK.
2. Validar que el formato del JSON resultante se corresponda con el estipulado.

Para ejecutarlos, simplemente se corre el siguiente comando desde la carpeta del proyecto:
```
php artisan test tests\Feature\YoutubeTest.php
```
<p align="center"><img src="https://i.imgur.com/cBc7Iox.png"></p>

## Cierre
Me alegra poder decir que el _‘Have fun!!’_ de la consigna del Challenge también fue cumplida, ¡Realmente entretenido el proyecto!

Para quien haya leído hasta aquí, ¡Muchas gracias y saludos!

_<p align="right">Matías Carabella - Back-end Developer</p>_
