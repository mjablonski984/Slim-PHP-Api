# Slim PHP REST Api
A simple RESTful API built with a Slim PHP micro framework. Data is stored in MySQL database.
<br/><br/>

# Installation and setup
1. Install Slim PHP and dependencies
    ```
    composer install
    ```
2. Import databse from migration.sql

3. Edit database params in src/config/db.php
<br/><br/>

# Endpoints and cURL test commands:
Modify your file structure to match test api routes (http://localhost/projects/slim-php-api).\
For testing purpose api requires fake bearer token: TEST_TOKEN. Auth middleware class must be modified to accept real tokens.
<br/><br/>

- ## Get all users
<br/>

### Request
```
GET /api/users
```
<br/>

```
curl -H "Accept: application/json" -H "Authorization: Bearer TEST_TOKEN" http://localhost/projects/slim-php-api/api/users
```
<br/>

### Response data
```
[{"id":"1","first_name":"John","last_name":"Doe","username":"Johndoe","created_at":"2021-04-04 17:00:00","dark_mode":"0"},
{"id":"2","first_name":"Jane","last_name":"Doe","username":"Janedoe","created_at":"2021-04-04 17:00:00","dark_mode":"0"},
...
]
```
<br/>

- ## Search users by keyword
<br/>

### Request
```
GET /api/users/search/{keyword}
```
<br/>

```
curl -H "Accept: application/json" -H "Authorization: Bearer TEST_TOKEN" http://localhost/projects/slim-php-api/api/users/search/john
```
<br/>

### Response data
```
[{"id":"1","first_name":"John","last_name":"Doe","username":"Johndoe","created_at":"2021-04-04 17:00:00","dark_mode":"0"}]
```
<br/>

- ## Add new user
<br/>

### Request
```
POST /api/users/add
```
<br/>

```
curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer TEST_TOKEN" \
--data '{"first_name":"Test", "last_name":"User", "username":"testuser"}' \
http://localhost/projects/slim-php-api/api/users/add
```
<br/>

### Response data
```
{"message":{"text":"User created"}}
```
<br/>

- ## Update username
<br/>

### Request
```
PATCH /api/users/update/{id}
```
<br/>

```
curl -X PATCH -H "Accept: application/json" -H "Authorization: Bearer TEST_TOKEN" \
http://localhost/projects/slim-php-api/api/users/update/5 --data "username"="Ansmith"
```
<br/>

### Response data
```
{"message":{"text":"Username updated"}}
```
<br/>

- ## Toggle dark mode
<br/>

### Request
```
PATCH /api/users/toggledarkmode/{id}
```
<br/>

```
curl -X PATCH -H "Accept: application/json" -H "Authorization: Bearer TEST_TOKEN" \
http://localhost/projects/slim-php-api/api/users/toggledarkmode/5
```
<br/>

### Response data
```
{"message":{"text":"Dark mode on"}}
```
<br/>

- ## Delete user
<br/>

### Request
```
DELETE /api/users/delete/{id}
```
<br/>

```
curl -X DELETE -H "Accept: application/json" -H "Authorization: Bearer TEST_TOKEN" \
http://localhost/projects/slim-php-api/api/users/delete/5
```
<br/>

### Response data
```
{"message":{"text":"User deleted"}}
```
<br/>