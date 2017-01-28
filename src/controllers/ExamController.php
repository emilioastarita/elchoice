<?php
namespace App\Controllers;

use Psr\Log\InvalidArgumentException;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class ExamController extends BaseController
{

    /*
     * If the user is not admin returns the user id
     * of the logged user to filter the records.
     */
    protected function getFilterId()
    {
        $user = $this->get('userSession')->getUser();
        $userId = false;
        if ($user['role'] !== 'admin') {
            $userId = $user['id'];
        }
        return $userId;
    }

    protected function getLoggedId()
    {
        $user = $this->get('userSession')->getUser();
        if (empty($user)) {
            throw new InvalidArgumentException('Invalid state app. No user logged');
        }
        return $user['id'];
    }

    public function collection(Request $req, Response $res)
    {
        $this->logger->info("List exams");
        $exams = $this->get('examService')->findAll();
        return $res->withJson($exams);
    }

    public function create(Request $req, Response $res)
    {
        $examService = $this->get('examService');
        $params = $req->getParams();
        $id = $this->getFilterId();
        if ($id) {
            $params['userId'] = $id;
        }
        if(empty($params['userId'])) {
            $params['userId'] = $this->getLoggedId();
        }
        $exam = $examService->create($params);
        return $res->withJson($exam->toArray());
    }

    public function view(Request $req, Response $res, $args)
    {
        $examService = $this->get('examService');
        $this->logger->info("Get exam");
        $exam = $examService->find($args['id']);
        if (empty($exam)) {
            throw new \Exception('No exam.');
        }
        return $res->withJson($exam->toArray(true));
    }

    public function modify(Request $req, Response $res, $args)
    {
        $examService = $this->get('examService');
        $this->logger->info("Put exam");
        $id = $this->getFilterId();
        $params = $req->getParams();
        if ($id) {
            $params['userId'] = $id;
        }
        $params['id'] = $args['id'];

        $exam = $examService->update($params, $params['userId']);
        if (empty($exam)) {
            throw new NotFoundException('No exam.');
        }
        return $res->withJson($exam->toArray());
    }

    public function delete(Request $req, Response $res, $args)
    {
        $examService = $this->get('examService');
        $this->logger->info("Delete exam route");
        $examService->delete($args['id'], $this->getFilterId());
    }
}