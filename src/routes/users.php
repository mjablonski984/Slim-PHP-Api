<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/../middleware/Auth.php';

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

// Get All Users
// Method: GET
// Url: /api/users
$app->get('/api/users', function(Request $request, Response $response) {
    $query = "SELECT * FROM users";

    try{
        $db = new Db();
        $db = $db->connect();
        $stmt = $db->query($query);

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        return $response->withJson($users,200);
    } catch(PDOException $err){
        return $response->withJson(['error' => ['text' => $err->getMessage()]]);
    }
})->add(new Auth());


// Search Users By Keyword
// Method: GET
// Url: /api/users/search/{keyword}
$app->get('/api/users/search/{keyword}', function(Request $request, Response $response) {
    $keyword = $request->getAttribute('keyword');
    $query = "SELECT * FROM users WHERE username LIKE '%$keyword%' OR first_name LIKE '%$keyword%' OR last_name  LIKE '%$keyword%'";

    try{
        $db = new Db();
        $db = $db->connect();
        $stmt = $db->query($query);

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        return $response->withJson($users,200);
    } catch(PDOException $err){
        return $response->withJson(['error' => ['text' => $err->getMessage()]]);
    }
})->add(new Auth());


// Create New User
// Method: POST
// Url: /api/users/add
$app->post('/api/users/add', function(Request $request, Response $response){
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $username = $request->getParam('username');

    if(strlen($username) < 6){
        return $response->withJson(['error' => ['text' => 'Username must be at least 6 character long']]);
    }

    $query = "INSERT INTO users (first_name,last_name,username) VALUES
    (:first_name,:last_name,:username)";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($query);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username);

        $stmt->execute();
        
        return $response->withJson(['message' => ['text' => 'User created']],201);
        
    } catch(PDOException $err){
        return $response->withJson(['error' => ['text' => $err->getMessage()]]);
    }
})->add(new Auth());


// Update Username
// Method: PATCH
// Url: /api/users/update/{id}
$app->patch('/api/users/update/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $username = $request->getParam('username');

    $sql = "UPDATE users SET username = :username WHERE id = $id";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':username', $username);

        $stmt->execute();

        return $response->withJson(['message' => ['text' => 'Username updated']]);

    } catch(PDOException $err){
        return $response->withJson(['error' => ['text' => $err->getMessage()]]);
    }
})->add(new Auth());


// Toggle Dark Mode
// Method: PATCH
// Url: /api/users/toggledarkmode/{id}
$app->patch('/api/users/toggledarkmode/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $query = "SELECT dark_mode FROM users WHERE id = $id";
    
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($query);

        $stmt->execute();
        $isDark = $stmt->fetch(PDO::FETCH_COLUMN);

        ((int)$isDark == 1) ? $isDark = 0 : $isDark = 1;       
        $sql = "UPDATE users SET dark_mode = $isDark WHERE id = $id";
        
        $stmt2 = $db->prepare($sql);
        $stmt2->execute();

        return $response->withJson(['message' => ['text' => "Dark mode ".($isDark == true ? 'on' : 'off')]]);

    } catch(PDOException $err){
        return $response->withJson(['error' => ['text' => $err->getMessage()]]);
    }
})->add(new Auth());


// Delete User
// Method: DELETE
// Url: /api/users/delete/{id}
$app->delete('/api/users/delete/{id}', function(Request $request, Response $response){
    
    $id = $request->getAttribute('id');
    $query = "DELETE FROM users WHERE id = $id";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($query);

        $stmt->execute();
        $db = null;

        return $response->withJson(['message' => ['text' => 'User deleted']],200);

    } catch(PDOException $err){
        return $response->withJson(['error' => ['text' => $err->getMessage()]]);
    }
})->add(new Auth());
