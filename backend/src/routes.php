<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'index.latte');
});

$app->get('/clear-rooms', function (Request $request, Response $response, array $args) {
	$model = new Rooms($this->db);
	try{
		$model->cleanUp();
		return $response->withStatus(200);
	} catch (Exception $ex) {
		$this->logger->error($ex->getMessage());
		return $response->withStatus(500);
	}
});

$app->get('/clear-users', function (Request $request, Response $response, array $args) {
	$users = new Users($this->db);
	$rooms = new Rooms($this->db);
	try{
		$users->cleanUp();		
		$rooms->cleanEmptyRooms();
		return $response->withStatus(200);
	} catch (Exception $ex) {
		$this->logger->error($ex->getMessage());
		return $response->withStatus(500);
	}
});

$app->get('/add-user', function (Request $request, Response $response, array $args) {
    $this->db->query('INSERT INTO users '
            .'(login, email, password, name, surname, gender, registered) '
            .'VALUES '
            ."('xdvorak', 'xdvorak@mendelu.cz', 'test', 'David', 'Dvorak', 'male', NOW())");          
    
    $tplVars['pokus'] = 'Hello world!';
    return $this->view->render($response, 'pokus.latte', $tplVars);
});


$app->get('/pokus', function (Request $request, Response $response, array $args) {
    $model = new Rooms($this->db);
    $model->add('test', 1);
    $model->add('test2', 2);
    print_r($model->all());
});

$app->get('/api/ui/{la}/{co}', function (Request $request, Response $response, array $args) {
	$model = new Ui($this->db);
	try{
    $data= $model->getLayoutLabels($args['la'],$args['co']);
	return $response->withJson($data);
	} catch (Exception $ex){
		$this->logger->error($ex->getMessage());
        return $response->withStatus(500);
    }
});

$app->get('/api/rooms', function (Request $request, Response $response, array $args) {
    $model = new Rooms($this->db);
    try{
        $rooms = $model->all();
        return $response->withJson($rooms);
    } catch (Exception $ex){
        $this->logger->error($ex->getMessage());
        return $response->withStatus(500);
    }
   
});

$app->get('/api/rooms/{id}', function (Request $request, Response $response, array $args) {
    if(!empty($args['id'])){
        $model = new Rooms($this->db);
       try{
           $info = $model->find($args['id']);
           if($info){
               return $response->withJson($info);
           }else{
               return $response->withStatus(404);
           }
       } catch (Exception $ex){
           $this->logger->error($ex->getMessage());
           return $response->withStatus(500);
       }
    } else {
        return $response->withStatus(400);
    }

    $model->find($args['id']);
    print_r($model->all());
    
});

$app->post('/api/rooms', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    if(!empty($data['title'])){
        $model = new Rooms($this->db);
        try{
            $model->add($data['title'], 1);
            return $response->withStatus(201);
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
            return $response->withStatus(500);
        }
    }else {
        return $response->withStatus(400);
    }
});

$app->post('/api/register', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    if(!empty($data['login']) && !empty($data['name']) && !empty($data['surname']) &&
        !empty($data['email']) && !empty($data['password']) && !empty($data['gender']))
      {  
        try{
          $stmt = $this->db->prepare('INSERT INTO users (name, surname, login, email, password, gender, registered) VALUES (:n, :s, :l, :e, :p, :g, NOW())');
          $p = password_hash($data['password'], PASSWORD_DEFAULT);
          $stmt->bindValue(':n', $data['name']);
          $stmt->bindValue(':s', $data['surname']);
          $stmt->bindValue(':l', $data['login']);
          $stmt->bindValue(':e', $data['email']);
          $stmt->bindValue(':p', $p);
          $stmt->bindValue(':g', $data['gender']);
          $stmt->execute();
          return $response->withStatus(201);
        }catch(Exception $ex){
          $this->logger->error($ex->getMessage());
          return $response->withStatus(500);
        }
      
      }else{
        return $response->withStatus(400);
      }
});

$app->post('/api/login', function (Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        if (!empty($data['pass']) && !empty($data['login'])){
           try{
			   $model = new Users($this->db);
			   $user = $model->verify($data['login'], $data['pass']);
			   if($user){

					$signer = new Sha256();
					$token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
											->setAudience('http://example.org') // Configures the audience (aud claim)
											->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
											->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
											->setNotBefore(time()) // Configures the time that the token can be used (nbf claim)
											->setExpiration(time() + 3600) // Configures the expiration time of the token (exp claim)
											->set('id', $user['id_users']) // Configures a new claim, called "uid"
											->set('login', $user['login'])
											->sign($signer, getenv('TOKEN_KEY')) // creates a signature using "testing" as key
											->getToken(); // Retrieves the generated token			
					
					 return $response->withJson(['token'=> (string) $token], 202);
			   }else{
					return $response->withStatus(404);
				}
           }catch(Exception $ex){
				$this->logger->error($ex->getMessage());
				return $response->withStatus(500);
           }
        }else{
			return $response->withStatus(400);
        }
});

$app->get('/logout', function (Request $request, Response $response, $args){
		session_destroy();
		return $response->withStatus(200);
	})->setName('logout');

$app->group('/api/auth', function() use ($app){
  
$app->get('/rooms', function (Request $request, Response $response, array $args) {
    $token = $request->getAttribute('token');
    $model = new Rooms($this->db);
    try{
		$data = array( 
		'rooms' => $model->all(),
		'info' => $model->getRoomsInfo()		
		);	
        return $response->withJson($data);
    } catch (Exception $ex){
        $this->logger->error($ex->getMessage());
        return $response->withStatus(500);
    }
});

$app->get('/my-rooms', function (Request $request, Response $response, array $args) {
    $token = $request->getAttribute('token');
    $model = new Rooms($this->db);
    try{
        $myrooms = $model->getRoomsByUser($token->getClaim('id'));
        return $response->withJson($myrooms);
    } catch (Exception $ex){
        $this->logger->error($ex->getMessage());
        return $response->withStatus(500);
    }
});

$app->post('/rooms', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    if(!empty($data['title'])){
        $model = new Rooms($this->db);
        try{
            $token = $request->getAttribute('token');
            $model->add($data['title'], $token->getClaim('id'), $data['roomlang']);
            return $response->withStatus(201);
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
            return $response->withStatus(500);
        }
    }else {
        return $response->withStatus(400);
    }
});

$app->get('/user/{id}', function (Request $request, Response $response, array $args) {
    if(!empty($args['id'])){
        $user = new Users($this->db);
		$id = $args['id'];
		try{
		$data = $user->getUserById($id);
		return $response->withJson($data);
		}catch(Exception $ex){
           $this->logger->error($ex->getMessage());
           return $response->withStatus(500);
		}
    } else {
		return $response->withStatus(400);
    }
});

$app->get('/profile', function (Request $request, Response $response, array $args) {
		$user = new Users($this->db);
		try{
			$token = $request->getAttribute('token');						
            $data = $user->getUserById($token->getClaim('id'));
			return $response->withJson($data);
		}catch(Exception $ex){
           $this->logger->error($ex->getMessage());
           return $response->withStatus(500);
		}	
});

$app->get('/invite-list/{rid}', function (Request $request, Response $response, array $args) {
		$room = $args['rid'];
		$user = new Users($this->db);
		try{						
            $data = $user->getOtherUsers($room);
			return $response->withJson($data);
		}catch(Exception $ex){
           $this->logger->error($ex->getMessage());
           return $response->withStatus(500);
		}	
});

$app->post('/edit-profile', function (Request $request, Response $response, array $args) {
	$data = $request->getParsedBody();
	$model = new Users($this->db);
	try{
		$token = $request->getAttribute('token');
		$model->editUser($data['name'], $data['surname'], $data['gender'], $token->getClaim('id'));
		return $response->withStatus(200);
	} catch (Exception $ex) {
		$this->logger->error($ex->getMessage());
		return $response->withStatus(500);
	}
});
	
$app->get('/messages/{id}', function (Request $request, Response $response, array $args) {
    if(!empty($args['id'])){
        $messages = new Messages($this->db);
		$users = new Users($this->db);
		$rooms = new Rooms($this->db);
		$id = $args['id'];
       try{			
			$token = $request->getAttribute('token');
            $data = array( 
		    'messages' => $messages->getMessages($id, $token->getClaim('id')),
			'rooms' => $rooms->getRoomInfo($id),
			'users' => $users->getUsersByRoom($id),
			'user' => $token->getClaim('id')
			);		   
            return $response->withJson($data);
       } catch (Exception $ex){
           $this->logger->error($ex->getMessage());
           return $response->withStatus(500);
       }
    } else {
        return $response->withStatus(400);
    }
});

$app->post('/messages/{id}', function (Request $request, Response $response, array $args) {
	$idRoom = $args['id'];
	$data = $request->getParsedBody();
	$messages = new Messages($this->db);
	$users = new Users($this->db);
	$rooms = new Rooms($this->db); 
	try{
		$token = $request->getAttribute('token');
		$messages->add($idRoom, $token->getClaim('id'), $data['message'], $data['userTo']);
		$data = array(
			'messages' => $messages->getMessages($idRoom),
			'rooms' => $rooms->getRoomInfo($idRoom),
			'users' => $users->getUsersByRoom($idRoom),
			'user' => $token->getClaim('id')
		);
		return $response->withJson($data);
	} catch (Exception $ex) {
		$this->logger->error($ex->getMessage());
		return $response->withStatus(500);
	}
});

$app->post('/leave-room/{id}', function (Request $request, Response $response, array $args) {
	$idRoom = $args['id'];
	$model = new Users($this->db);
	try{
		$token = $request->getAttribute('token');
		$model->leaveRoom($idRoom, $token->getClaim('id'));
		return $response->withStatus(200);
	} catch (Exception $ex) {
		$this->logger->error($ex->getMessage());
		return $response->withStatus(500);
	}
});

$app->post('/rename-room/{id}/{title}', function (Request $request, Response $response, array $args) {
	$idRoom = $args['id'];
	$title = $args['title'];
	$model = new Rooms($this->db);
	try{
		$model->renameRoom($idRoom, $title);
		return $response->withStatus(200);
	} catch (Exception $ex) {
		$this->logger->error($ex->getMessage());
		return $response->withStatus(500);
	}
});

$app->post('/lock-room/{idr}', function (Request $request, Response $response, array $args) {
	$idRoom = $args['idr'];
	$model = new Rooms($this->db);	
	try{		
		$token = $request->getAttribute('token');
		$token->getClaim('id');
		$done = $model->lockRoom($idRoom,$token->getClaim('id'));
		if($done){		
			return $response->withStatus(200);
		}else{
			return $response->withStatus(401, 'User not admin');
		}
	} catch (Exception $ex) {
		$this->logger->error($ex->getMessage());
		return $response->withStatus(500);
	}
});

$app->post('/in-room/{rid}', function (Request $request, Response $response, array $args) {
	$idRoom = $args['rid'];
	$model = new Users($this->db);
	try{
		$token = $request->getAttribute('token');
		$lock = $model->addRoomUser($idRoom, $token->getClaim('id'));
		if(!$lock){
			return $response->withStatus(200);
		}else{
			return $response->withStatus(401, 'Locked room');			
		}
	} catch (Exception $ex) {
		$this->logger->error($ex->getMessage());
		return $response->withStatus(500);
	}
});

$app->post('/add-user/{rid}/{uid}', function (Request $request, Response $response, array $args) {
	$room = $args['rid'];
	$user = $args['uid'];
	$model = new Rooms($this->db);
	try{
		$model->addUser($room, $user);
		return $response->withStatus(200);
	} catch (Exception $ex) {
		$this->logger->error($ex->getMessage());
		return $response->withStatus(500);
	}
});

$app->post('/kick-user/{rid}/{uid}', function (Request $request, Response $response, array $args) {
	$idRoom = $args['rid'];
	$user = $args['uid'];
	$rooms = new Rooms($this->db);
	$messages = new Messages($this->db);
	$users = new Users($this->db);		
	$token = $request->getAttribute('token');
	try{
		$rooms->kickUser($idRoom, $user);
		$data = array(
			'messages' => $messages->getMessages($idRoom),
			'rooms' => $rooms->getRoomInfo($idRoom),
			'users' => $users->getUsersByRoom($idRoom),
			'user' => $token->getClaim('id')
		);
		return $response->withJson($data);
	} catch (Exception $ex) {
		$this->logger->error($ex->getMessage());
		return $response->withStatus(500);
	}
});

})->add(function (Request $request, Response $response, $next){
    $rawToken = $request->getHeaderLine('Authorization');
    	
    if($rawToken){
		$signer = new Sha256();
        $data = new ValidationData();
        $data->setIssuer('http://example.com');
        $data->setAudience('http://example.org');
        $data->setId('4f1g23a12aa');

        $token = (new Parser())->parse((string) $rawToken);
		
        if($token->validate($data) && $token->verify($signer, getenv('TOKEN_KEY'))) {
            $request = $request->withAttribute('token', $token);
            return $next($request, $response);
        }
    }
    return $response->withStatus(401, 'Invalid token');
  
});