<?php
namespace App\Controllers;

use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class MemberController extends BaseController
{

    public function loginStatus(Request $req, Response $res)
    {
        $role = $this->get('userSession')->getUserRole();
        return $res->withJson(['login' => $role !== 'guest', 'role' => $role], 200);
    }
    public function login(Request $req, Response $res)
    {
        $this->logger->info("Login request");
        if (empty($req->getParam('username'))) {
            return $res->withJson(['error' => 'Missing username.'], 401);
        }
        if (empty($req->getParam('password'))) {
            return $res->withJson(['error' => 'Missing password.'], 401);
        }
        $user = $this->get('userService')
                     ->findWithCredentials($req->getParam('username'), $req->getParam('password'));
        if (!$user) {
            return $res->withJson(['error' => 'Invalid username or password.'], 401);
        }
        $this->get('userSession')->setUser($user);
        return $res->withJson(['login' => true, 'role' => $user['role']], 200);
    }

    public function logout(Request $req, Response $res)
    {
        session_destroy();
        return $res->withJson(['logout' => true], 200);
    }

    public function register(Request $req, Response $res, $args)
    {
        $userService = $this->get('userService');
        $params = $req->getParams();
        $params['role'] = 'user';
        $user = $userService->create($params);
        return $res->withJson($user->toArray(), 200);
    }

    public function modify(Request $req, Response $res, $args)
    {
        $userService = $this->get('userService');
        $this->logger->info("Put user");
        $user = $userService->find($args['id']);
        $params = $req->getParams();
        $params['id'] = $args['id'];
        $user = $userService->update($params);
        if (empty($user)) {
            throw new NotFoundException('No user.');
        }
        return $res->withJson($user->toArray());
    }

    public function delete(Request $req, Response $res)
    {
        $userService = $this->get('userService');
        $this->logger->info("Delete user route");
    }
}