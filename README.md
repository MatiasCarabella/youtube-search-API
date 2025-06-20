<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://i.imgur.com/ckGOMl1.png" width="400"></a></p>

<div align="center">
    <a href="https://www.php.net/" target="_blank"><img src="https://img.shields.io/badge/dynamic/json?url=https%3A%2F%2Fraw.githubusercontent.com%2FMatiasCarabella%2Fyoutube-search-API%2Fmain%2Fcomposer.json&query=%24.require.php&label=PHP&labelColor=777BB4&color=gray&logo=php&logoColor=white" alt="PHP" /></a>
    <a href="https://laravel.com/" target="_blank"><img src="https://img.shields.io/badge/dynamic/json?url=https%3A%2F%2Fraw.githubusercontent.com%2FMatiasCarabella%2Fyoutube-search-API%2Fmain%2Fcomposer.json&query=%24.require%5B%27laravel%2Fframework%27%5D&label=Laravel&labelColor=FF2D20&color=gray&logo=laravel&logoColor=white" alt="Laravel" /></a>
    <a href="https://getcomposer.org/" target="_blank"><img src="https://img.shields.io/badge/Composer-885630?logo=composer&logoColor=white" alt="Composer" /></a>
    <a href="https://www.docker.com/" target="_blank"><img src="https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=fff" alt="Docker" /></a>
    <a href="https://documenter.getpostman.com/view/10146128/2s93JoxRFG" target="_blank"><img src="https://img.shields.io/badge/Postman-Collection-orange?logo=postman" alt="Postman Collection" /></a>
</div>

# Youtube Search API - [Aivo](https://www.aivo.co/) Technical Challenge

### General requirement
 Develop an endpoint that, given a keyword, returns up to 10 results from a YouTube search.

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
    "published_at": "2011-10-19T02:42:54Z",
    "id": "1G4isv_Fylg",
    "title": "Coldplay - Paradise (Official Video)",
    "description": "Coldplay - Paradise is taken from the album Mylo Xyloto released in 2011 [...]",
    "thumbnail": "https://i.ytimg.com/vi/1G4isv_Fylg/default.jpg",
    "extra": {
        "direct_link": "https://www.youtube.com/watch?v=1G4isv_Fylg",
        "channel_title": "Coldplay"
    }
}
```

The list of fetched videos will be contained in a **_videos_** parameter, which will share the 'top level' of the response JSON with the following general parameters:
- **_total_results_**
- **_results_per_page_**
- **_next_page_token_** **(*)** _(makes it possible to navigate between the result pages)_
- **_prev_page_token_** **(*)** _(same as ‘next_page_token’)_


**(*)** - The **_next_page_token_** parameter will only appear if there are more pages of results, likewise, **_prev_page_token_** will only appear if we are not positioned on page 1.

Finally, the general format of the response will be as follows:

```json
{
    "next_page_token": "CAEQAA",
    "total_results": 1000000,
    "results_per_page": 1,
    "videos": []
}
```

## Project guidelines
- _Must be developed in **PHP7**:_
    - _13/03/2021:_ Developed using PHP's newest version as of today: **PHP 7.4.16**
    - _26/02/2023:_ Upgraded to  **PHP 8.2.3**
    - _18/06/2025:_ Upgraded to  **PHP 8.4.8**
- _**Framework** of your choice (Optional):_
    - _13/03/2021:_ Developed with **Laravel 8**
    - _26/02/2023:_ Upgraded to **Laravel 10**
    - _18/06/2025:_ Upgraded to **Laravel 12**
- _The project must be available on **GitHub** or **BitBucket**:_
    - **GitHub** was chosen, in the present repository: <a href="https://github.com/MatiasCarabella/youtube-search-API">_MatiasCarabella/youtube-search-API (github.com)_</a>
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

1. Clone the project and `cd` into it:

```
git clone https://github.com/MatiasCarabella/youtube-search-API.git
cd youtube-search-API
```

2. Copy the .env file _(optional: Set a custom **API_KEY_DEFAULT** value)_

- Windows: `copy .env.example .env`
- Linux: `cp .env.example .env`

Now, in order to get the application up and running, you have two options:
### PHP 🐘 | Composer 🎻
Pre-requisites:
- <a href="https://www.php.net/">**PHP8**</a> installed
- <a href="https://getcomposer.org/">**Composer**</a> installed

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

### Docker 🐋
Pre-requisites:
- <a href="https://www.docker.com/">**Docker**</a> installed
- <a href="https://docs.docker.com/compose/">**Docker Compose**</a> installed

1. Run the following command in the project root folder to build & start the application:
```
docker compose up
```

**All done!**


## Using the API

Onto the _fun_ bit, now we're able to use the API. The URL of the endpoint is the following:

_**<p align="center">http://localhost:8000/api/youtube-search</p>**_

If we access this URL from a web browser, we'll see something like this:
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "search": [
      "The search field is required."
    ]
  }
}
```
That's to be expected _(since we haven't specified the text to search for)_, but it gives us confirmation that the API is running successfully!

Now, to effectively test the service we can use a client like <a href="https://www.postman.com/">**Postman**</a>:

![image](https://github.com/user-attachments/assets/b3f46aa3-ca42-42db-9aac-d06a41c97394)

As you can see, it's simply a matter of sending a 'search' query param with the text to search 🔍

The only other mandatory element is the auth via **api_key** 🔐, which can be generated on the <a href="https://console.developers.google.com/apis/credentials">**Google Cloud Platform**</a>.

This can be configured in two ways:
- As a Request **header** _('api_key': 'XXXXXXXXXXXXX')_
- As the value of the **_API_KEY_DEFAULT_** variable from the projects' **env** file _(When not sent as a header, it is read from here)_

```
API_KEY_DEFAULT=XXXXXXXXXXXXX
```

In both cases, if an invalid **api_key** is set _(or if there's no api_key)_, an error will be displayed as returned by the Google API:

![image](https://github.com/user-attachments/assets/57b16a9c-dfec-434b-a7fb-5a6119007a7c)

Finally, as we mentioned earlier, the optional fields **_results_per_page_** and **_page_token_** are also available.

![image](https://github.com/user-attachments/assets/9e96dad8-709b-420b-b967-19c9b2c7f838)

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

## Formatting

This project uses [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) to ensure consistent code style.

To automatically format your code, run:
```
composer format
```
If you are using PHP 8.4 or higher, you may need to bypass the version check:
```
$env:PHP_CS_FIXER_IGNORE_ENV=1; composer format
```
(For PowerShell. For Bash: `PHP_CS_FIXER_IGNORE_ENV=1 composer format`)

The formatting rules are defined in the `.php-cs-fixer.php` file at the project root.

## Tests

There are some tests that can be run to make sure the application functions properly. These are:

1. Validate that an example query returns HTTP status 200 - OK.
2. Validate that the JSON response format matches to the stipulated one.

To execute them, simply run the following command from the project root folder:
```
php artisan test
```
<p align="center"><img src="https://github.com/user-attachments/assets/63ed6996-5143-4d29-8924-323f2b8b1be0"></p>

## Closing thoughts
I am happy to say that the _‘Have fun!’_ bit from the Challenge's description was also achieved, I really enjoyed the project!

Special thanks to [@DaianaArena](https://github.com/DaianaArena) for creating the banner image.

To whoever read this far, thank you very much and best regards!

_<p align="right">Matías Carabella - Back-end Developer</p>_
