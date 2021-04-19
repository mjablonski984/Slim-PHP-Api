<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/../middleware/Auth.php';
require __DIR__.'/../controllers/UserController.php';

$app = new \Slim\App;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
});


// /api/users route group
$app->group('/api/users', function($app) {

// Get All Users
// GET: /api/users
$app->get('', '\UserController:getAll');

// Search Users By Keyword
// GET: /api/users/search/{keyword}
$app->get('/search/{keyword}', '\UserController:search');

// Create New User
// POST: /api/users/add
$app->post('/add', '\UserController:create');

// Update Username
// PATCH: /api/users/update/{id}
$app->patch('/update/{id}', '\UserController:update');

// Toggle Dark Mode
// PATCH: /api/users/toggledarkmode/{id}
$app->patch('/toggledarkmode/{id}', '\UserController:toggleDarkMode');

// Delete User
// DELETE: /api/users/delete/{id}
$app->delete('/delete/{id}', '\UserController:delete');

})->add(new Auth()); // Add authentication middleware
