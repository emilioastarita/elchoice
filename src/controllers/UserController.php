<?php
namespace App\Controllers;

use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class UserController extends BaseController
{

    public function collection(Request $req, Response $res)
    {
        $this->logger->info("List users");
        $users = $this->get('userService')->findAll();
        return $res->withJson($users);
    }

    public function create(Request $req, Response $res)
    {
        $userService = $this->get('userService');
        $user = $userService->create($req->getParams());
        return $res->withJson($user->toArray());
    }

    public function view(Request $req, Response $res, $args)
    {
        $userService = $this->get('userService');
        $this->logger->info("Get user");
        $user = $userService->find($args['id']);
        if (empty($user)) {
            throw new NotFoundException('No user.');
        }
        return $res->withJson($user->toArray());
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

    public function delete(Request $req, Response $res, $args)
    {
        $userService = $this->get('userService');
        $this->logger->info("Delete user route");
        $userService->delete($args['id']);
    }
}