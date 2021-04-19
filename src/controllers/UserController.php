<?php
use Psr\Container\ContainerInterface;

class UserController
{
   protected $container;

   // Constructor receives container instance
   public function __construct(ContainerInterface $container) {
       $this->container = $container;
   }

    // Check if user with given id exists
    private function userExist($request, $response, $id) {
        $query = "SELECT * FROM users WHERE id = $id";

        try{
            $db = new Db();
            $db = $db->connect();
            $stmt = $db->query($query);

            $user = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;

            return $user ? true : false;
        } catch(PDOException $err){
            return $response->withJson(['error' => ['text' => $err->getMessage()]]);
        }
    }


   // Get All Users
    public function getAll($request, $response, $args) {
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
    }
    

    // Search Users By Keyword
    public function search($request, $response, $args) {
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
    }


    // Create New User
    public function create($request, $response, $args) {
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
    }


    // Update Username
    public function update($request, $response, $args) {
        $id = $request->getAttribute('id');
        $username = $request->getParam('username');

        // Check if user exists
        if ($this->userExist($request, $response,$id) == null) {
            return $response->withJson(['error' => ['text' => 'User with this id does not exist']],404);
        }

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
    }

    // Toggle Dark Mode
    public function toggleDarkMode($request, $response, $args) {
        $id = $request->getAttribute('id');
        
        // Check if user exists
        if ($this->userExist($request, $response,$id) == null) {
            return $response->withJson(['error' => ['text' => 'User with this id does not exist']],404);
        }

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
    }

    // Delete User
    public function delete($request, $response, $args) {  
        $id = $request->getAttribute('id');

        // Check if user exists
        if ($this->userExist($request, $response,$id) == null) {
            return $response->withJson(['error' => ['text' => 'User with this id does not exist']],404);
        }

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
    }
}