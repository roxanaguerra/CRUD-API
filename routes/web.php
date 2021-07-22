<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get('hola', function () use ($router) {
    return "Hola mundo";
});

$router->get('/libros', 'LibroController@index');

// CRUD - APIs
$router->post('/libros', 'LibroController@guardar');

$router->get('/libros/{id}', 'LibroController@ver');

$router->post('/libros/{id}', 'LibroController@actualizar');

$router->delete('/libros/{id}', 'LibroController@eliminar');