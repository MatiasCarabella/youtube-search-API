<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://i.imgur.com/ckGOMl1.png" width="400"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Youtube Search API - Aivo Challenge - Technical Document


### General requirement
 Develop an endpoint that returns up to 10 results from a YouTube search, given a keyword

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
    - _(Update 26/02/2023):_ Upgraded to **Laravel 10**
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

1. Generate the folder where you'll download the project, access it from the terminal/console of preference and run the following commands:

```
git init
git pull https://github.com/MatiasCarabella/youtubeSearchAPI.git
```

2. Copy the .env file _(Optional: Set a custom **API_KEY_DEFAULT** value)_

`copy .env.example .env` _(Windows)_ 

`cp .env.example .env` _(Linux)_

Now, in order to get the application up and running, you have two options:
### PHP | Composer | Artisan
Requirements:
- <a href="https://www.php.net/">**PHP8**</a> installed.
- <a href="https://getcomposer.org/">**Composer**</a> installed (dependency manager).

1. Run the following command to download the necessary dependencies:

```
composer install
```

2. Generate the encryption key
```
php artisan key:generate
```

3. Now you can run the project from its root folder whenever you want, using the following command:
```
php artisan serve
```

**All done!**

### Docker compose 🐋
Requirements:
- <a href="https://www.docker.com/">**Docker**</a> installed.

1. Run the following command in the project root folder to build & start the application:
```
docker compose up
```

**All done!**


## Using the API

Onto the _fun_ bit, now we're able to use the API. The URL of the endpoint is the following:

_**<p align="center">http://localhost:8000/api/youtube-search</p>**_

If we access this URL from a web browser, we'll see something like this:

<p align="center"><img src="https://i.imgur.com/pbnIuWg.png"></p>

That's to be expected _(since we haven't specified the text to search for)_, but it gives us confirmation that the API is running successfully!

Now, to effectively test the service we can use a client like <a href="https://www.postman.com/">**Postman**</a>:

<p align="center"><img src="https://i.imgur.com/LcEnhgM.png"></p>
As you can see, it's simply a matter of sending a JSON body with the text to search for in the 'search' field:

```json
{
    "search": "Paradise"
}
```

The only other mandatory element is the **api_key**, which can be generated on the <a href="https://console.developers.google.com/apis/credentials">**Google Cloud Platform**</a>.

This can be configured in two ways:

- As a Request **header** _('api_key': 'XXXXXXXXXXXXX')_

- As the value of the **_API_KEY_DEFAULT_** variable from the projects' **env** file _(When not sent as a header, it is read from here)_

```
API_KEY_DEFAULT=XXXXXXXXXXXXX
```

In both cases, if an invalid **api_key** is set _(or if there's no api_key)_, an error will be displayed as returned by the Google API:
<p align="center"><img src="https://i.imgur.com/1HWHXzm.png"></p>

Finally, as we mentioned earlier, the optional fields **_results_per_page_** and **_page_token_** are also available.

<p align="center"><img src="https://i.imgur.com/j5ZgZKa.png"></p>

### Notes

- If an invalid value is entered in the **_results_per_page_** parameter, it defaults to **10**.
- If an invalid value is entered in the **_page_token_** or **_api_key_** parameters, an error message will be displayed as returned by the Google API.

## Project structure

The bulk of the application's logic is on the following files:

`routes->api.php`

`app->Http->Controllers->YoutubeController.php`

`app->Services->YoutubeServices.php`

`tests->Feature->YoutubeTest.php`

In order to facilitate the understanding of the code, everything is commented accordingly:

<p align="center"><img src="https://i.imgur.com/X4R7C6M.png"></p>

## Tests

There are some tests that can be run to make sure the application functions properly. These are:

1. Validate that an example query returns HTTP status 200 - OK.
2. Validate that the JSON response format matches to the stipulated one.

To execute them, simply run the following command from the project root folder:
```
php artisan test tests/Feature/YoutubeTest.php
```
<p align="center"><img src="https://i.imgur.com/cBc7Iox.png"></p>

## Closing thoughts
I am happy to say that the _‘Have fun!’_ bit from the Challenge's description was also achieved, I really enjoyed the project!

Special thanks to [@DaianaArena](https://github.com/DaianaArena) for creating the banner image.

To whoever read this far, thank you very much and best regards!

_<p align="right">Matías Carabella - Back-end Developer</p>_
