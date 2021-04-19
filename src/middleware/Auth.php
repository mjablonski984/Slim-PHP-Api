<?php
class Auth
{
    public function __invoke($request, $response, $next)
    {   
        if ($request->hasHeader('Authorization')) {
            $authHeader = $request->getHeader('Authorization');
            $bearerToken = '';
            // If only one authorisation scheme is provided, loop below can be replaced with $bearerToken = '$authHeader[0];
            foreach ($authHeader as $token) {
                if (stripos($token, 'Bearer ') == 0) {
                    $bearerToken = $token;
                    break;
                }else{
                    $data = ['error' => ['text' => 'Authorization token not found']];
                    return $response->withJson($data,403);
                }
            }
            
            $token = substr($bearerToken,7);
            /*
                When using a real token the code below must be modified and token provided in the request header 
                must be compared to a users token in the database (A new db table is required to store valid tokens)
            */

            if ($token == "TEST_TOKEN") {
                $response = $next($request, $response);
                return $response;
            }else{
                $data = ['error' => ['text' => 'Invalid token']];
                return $response->withJson($data,403);
            }
 
        }else{
            $data = ['error' => ['text' => 'Authorization token not found']];
            return $response->withJson($data,403);
        }
    }
}