# Portfolio Backend

This PHP router powers my backend with an MVC architecture, utilizing GitHub Actions for seamless automatic deployment. It also relies on Docker Compose to easily set up a local development environment. You can start the local dev envoirment with

```
docker compose up -d
```

## PHP Router

This is a proof of concept (POC) for a parser which can parse clean dynamic urls.

## How to use

### Include

Note if you go to the public folder, then open index.php, you can see the error handling and the includes

```php
// The lib
require_once __DIR__ . '/../lib/autoload.php';
// Controllers
require_once __DIR__ . '/../app/autoload.php';
// Config
require_once __DIR__ . '/../config/config.php';
```

### Make an instance

```php

$router = new Router();
```

### Add one or more routes

In this case [c_id] and [s_id] ar dynamic vars, it supports a controller as well. It supports also methods like GET, POST, PUT, DELETE and HEAD. You can find the rout config in routes/api.php.

```php
Router::get('/company/[c_id]/staff/[s_id]/', [company::class, 'method']);
Router::post('/company/[c_id]/staff/[s_id]/', [company::class, 'method']);
```

### Inline usage

If you want to use it without a controller

```php
Router::get('/company/[c_id]/staff/[s_id]/', function(Request $r){
    $response = [
        'vars' => $r->params('s_id')
    ];

    return $response;
});
```

### Request and Response

This is an inline function example, assume that $r is an instance of Request

```php
    // This is an url fraction
    $s_id = $r->params('s_id')

    // This is post data, it also converts json input to post data, in this case we are requesting s_id
    $post= $r->post('s_id')
    // if you leave it empty, you will get all the post data back
    $post= $r->post('')

    // You can do the same with get and server
    $get = $r->get();
    $server = $r->server();

    // this converts the array to json
    $response = new Response([
        'vars' => $r->params('s_id')
    ]);
    $response->toJson();
```

### Run everything

We assign it to the var runner, so we can use it later, see index.php for an example

```php
$router = $router->run();
```

### Project structure

The structure of the project, you can find example files in this repo, like the routes, controllers, etc

- app
  - Controllers
  - autoload.php
- config
  - config.php
- lib
  - Auth
  - Curl
  - Database
  - Router
- public
  - Index.php <-- Start
- routes

## Lifecycle of the Application

Here is a description of how the processes things and its lifecycle.

| Boot order            | Description                                                                                                     |
| --------------------- | --------------------------------------------------------------------------------------------------------------- |
| Bootstrap             | When you do your request                                                                                        |
| Autoload              | Autoload the things in order to work                                                                            |
| Router init           | Makes a router object that can run during the request                                                           |
| Checks url            | It checks the urls and checks if there is a match                                                               |
| Parses url            | Now it is mapping the dynamic variables                                                                         |
| Create Request object | Makes a new Request object and prepares the post data and url fraction, so that you can used them in your logic |
| Execute logic         | Executes the controller or function (your logic)                                                                |
| Final response        | Gives the right response code                                                                                   |

## Compatibility

PHP >= 8.4
