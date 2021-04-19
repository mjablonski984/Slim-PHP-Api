<?php
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;

class UsersTest extends \PHPUnit\Framework\TestCase
{

  private $app;
  
  protected function setUp() : void 
  {
    $this->app = (new App());
  }

    // public function testGetAllUsers() {
    //   $env = Environment::mock([
    //       'REQUEST_METHOD' => 'GET',
    //       'REQUEST_URI'    => '/api/users'
    //       ]);
    //   $req = Request::createFromEnvironment($env);
    //   // print_r("REQUEST: ".$req);

    //   $this->app->getContainer()['request'] = $req;
    //   $response = $this->app->run(true);
    //   // print_r("RESPONSE: ".$response);

    //   $this->assertSame($response->getStatusCode(), 200);     
    //   $result = json_decode($response->getBody(), true);
    //   // print_r("RESULT: ".$result);
    // } 

}