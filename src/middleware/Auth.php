<?php
class Auth
{
    public function __invoke($request, $response, $next)
    {   
        if ($request->hasHeader('Authorization')) {
            $authHeader = $request->getHeader('Authorization');
            $bearerToken = '';
            //  Usually only one authorisation scheme is provided, loop below could be replaced with $bearerToken = '$authHeader[0];
            foreach ($authHeader as $token) {
                if (stripos($token, 'Bearer ') == 0) {
                    $bearerToken = $token;
                    break;
                }else{
                    $data = array(['error' => ['text' => 'Authorization token not found']]);
                    return $response->withJson($data,403);
                }
            }
            
            $token = substr($bearerToken,7);
            /*
                When using a real token (e.g. JWT) the code below must be modified.
                Token provided in the request header must be compared to a users token in your database.
            */

            if ($token == "TEST_TOKEN") {
                $response = $next($request, $response);
                return $response;
            }else{
                $data = array(['error' => ['text' => 'Invalid token']]);
                return $response->withJson($data,403);
            }
 
        }else{
            $data = array(['error' => ['text' => 'Authorization token not found']]);
            return $response->withJson($data,403);
        }
    }
}